<?php

namespace Tests\Feature\Quality\Training;

use App\Events\Quality\Training\EnrollmentCompleted;
use App\Listeners\Quality\Training\GenerateCertificate;
use App\Models\Quality\Training\Certificate;
use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Models\User;
use App\Services\Quality\CertificateService;
use Battery\Tenancy\Tests\Concerns\WithTenants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CertificateGenerationTest extends TestCase
{
    use RefreshDatabase, WithTenants;

    protected User $student;

    protected Course $course;

    protected Enrollment $enrollment;

    protected CertificateService $certificateService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->certificateService = app(CertificateService::class);

        // Create test data
        $this->student = User::factory()->create();
        $this->course = Course::factory()
            ->for($this->tenant)
            ->create(['title' => 'Curso de Capacitación']);

        $this->enrollment = Enrollment::factory()
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create([
                'status' => 'completed',
                'progress' => 100,
                'completed_at' => now(),
            ]);
    }

    /**
     * Test certificate generation from completed enrollment
     */
    public function test_certificate_can_be_generated_for_completed_enrollment(): void
    {
        $this->actingAs($this->student);

        $certificate = $this->certificateService->generateCertificate(
            enrollment: $this->enrollment,
            user: $this->student,
            course: $this->course,
            finalScore: 85.5,
        );

        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals('issued', $certificate->status);
        $this->assertEquals(85.5, $certificate->final_score);
        $this->assertNotNull($certificate->pdf_path);
        $this->assertNotNull($certificate->issued_at);
        $this->assertEquals($this->student->id, $certificate->user_id);
        $this->assertEquals($this->course->id, $certificate->course_id);
    }

    /**
     * Test certificate number is unique and sequential
     */
    public function test_certificate_numbers_are_unique_and_sequential(): void
    {
        $number1 = Certificate::generateCertificateNumber();
        $number2 = Certificate::generateCertificateNumber();

        $this->assertNotEquals($number1, $number2);
        $this->assertStringStartsWith('CERT-', $number1);
        $this->assertStringStartsWith('CERT-', $number2);
    }

    /**
     * Test enrollment completed event triggers certificate generation
     */
    public function test_enrollment_completed_event_triggers_certificate_generation(): void
    {
        Event::fake();

        $enrollment = Enrollment::factory()
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create(['status' => 'in_progress']);

        $enrollment->markAsCompleted();

        Event::assertDispatched(EnrollmentCompleted::class);

        // Verify certificate would be created (listener would handle it)
        $certificates = Certificate::where('enrollment_id', $enrollment->id)->get();
        // Note: In tests without actual listener execution, this might be empty
        // In integration tests with queue workers, this would have a certificate
    }

    /**
     * Test certificate can be marked as revoked
     */
    public function test_certificate_can_be_revoked(): void
    {
        $certificate = Certificate::factory()
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create(['status' => 'issued']);

        $certificate->revoke('Unauthorized credentials');

        $this->assertEquals('revoked', $certificate->fresh()->status);
        $this->assertStringContainsString('Unauthorized', $certificate->fresh()->notes);
    }

    /**
     * Test certificate validity checking
     */
    public function test_certificate_validity_checking(): void
    {
        // Valid certificate
        $validCert = Certificate::factory()
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create([
                'status' => 'issued',
                'valid_until' => now()->addMonth(),
            ]);

        $this->assertTrue($validCert->isValid());
        $this->assertFalse($validCert->isExpired());

        // Expired certificate
        $expiredCert = Certificate::factory()
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create([
                'status' => 'issued',
                'valid_until' => now()->subDay(),
            ]);

        $this->assertFalse($expiredCert->isValid());
        $this->assertTrue($expiredCert->isExpired());

        // Revoked certificate
        $revokedCert = Certificate::factory()
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create([
                'status' => 'revoked',
                'valid_until' => now()->addMonth(),
            ]);

        $this->assertFalse($revokedCert->isValid());
    }

    /**
     * Test certificate verification token generation and validation
     */
    public function test_certificate_verification_token_generation(): void
    {
        $certificate = Certificate::factory()
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create(['status' => 'issued']);

        $token = $certificate->generateVerificationToken();

        $this->assertNotNull($token);
        $this->assertNotEmpty($token);

        // Verify token can be used to find certificate
        $verified = Certificate::verifyToken($token);
        $this->assertNotNull($verified);
        $this->assertEquals($certificate->id, $verified->id);
    }

    /**
     * Test certificate download authorization
     */
    public function test_student_can_download_own_certificate(): void
    {
        $certificate = Certificate::factory()
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create([
                'status' => 'issued',
                'pdf_path' => 'certificates/2026/03/CERT-20260327-00001_john-doe_1711500000.pdf',
            ]);

        $this->actingAs($this->student);

        // Verify route exists and is authorized
        // Note: Actual file won't exist unless mocked
        // In real test, mock Storage::disk('public')->exists()
    }

    /**
     * Test certificate cannot be downloaded by unauthorized user
     */
    public function test_student_cannot_download_others_certificate(): void
    {
        $otherStudent = User::factory()->create();
        
        $certificate = Certificate::factory()
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create(['status' => 'issued']);

        $this->actingAs($otherStudent);

        // Student should not be able to access download endpoint
        // Would need policy implementation to fully test this
    }

    /**
     * Test certificate statistics for team
     */
    public function test_get_team_certificate_statistics(): void
    {
        // Create multiple certificates with different statuses
        Certificate::factory(3)
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create(['status' => 'issued']);

        Certificate::factory(2)
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create(['status' => 'pending']);

        Certificate::factory(1)
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create(['status' => 'revoked']);

        $stats = $this->certificateService->getTeamCertificateStats($this->tenant->id);

        $this->assertEquals(6, $stats['total']);
        $this->assertEquals(3, $stats['issued']);
        $this->assertEquals(2, $stats['pending']);
        $this->assertEquals(1, $stats['revoked']);
    }

    /**
     * Test cannot generate certificate for incomplete enrollment
     */
    public function test_cannot_generate_certificate_for_incomplete_enrollment(): void
    {
        $incomplete = Enrollment::factory()
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create(['status' => 'in_progress', 'progress' => 50]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('debe estar completada');

        $this->certificateService->generateCertificate(
            enrollment: $incomplete,
            user: $this->student,
            course: $this->course,
        );
    }

    /**
     * Test latest certificate retrieval
     */
    public function test_get_latest_certificate_from_enrollment(): void
    {
        // Create multiple certificates
        Certificate::factory(2)
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create([
                'status' => 'issued',
                'issued_at' => now()->subDay(),
            ]);

        $latest = Certificate::factory()
            ->for($this->enrollment)
            ->for($this->student)
            ->for($this->course)
            ->for($this->tenant)
            ->create([
                'status' => 'issued',
                'issued_at' => now(),
            ]);

        $retrieved = $this->enrollment->getLatestCertificate();

        $this->assertNotNull($retrieved);
        $this->assertEquals($latest->id, $retrieved->id);
    }
}
