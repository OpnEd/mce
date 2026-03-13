<?php

namespace App\Models;

use App\Models\Api\ExternalOrder;
use App\Models\Api\ExternalOrderTeamCandidate;
use App\Models\Residuo;
use App\Models\Quality\WasteGenerationReport;
use App\Models\Quality\ManagementIndicatorTeam;
use App\Models\Quality\ProcessTeam;
use App\Models\Quality\QualityGoal;
use App\Models\Quality\QualityGoalTeam;
use App\Models\Quality\Records\Clients\ClientPqrsRecord;
use App\Models\Quality\Records\Clients\ClientSatisfactionEvaluation;
use App\Models\Quality\Records\Cleaning\StablishmentArea;
use App\Models\Quality\Records\Cleaning\CleaningImplement;
use App\Models\Quality\Records\Cleaning\CleaningRecord;
use App\Models\Quality\Records\Cleaning\Desinfectant;
use App\Models\Quality\Records\Products\DispenseRecord;
use App\Models\Quality\Records\Products\MissingProduct;
use App\Models\Quality\Training\Enrollment;
use App\Models\Quality\Training\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se asignarán de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'identification',
        'address',
        'email',
        'phonenumber',
        'data',
        'establishment_id',
        'registration_number',
        'team_name',
        'location_1',
        'location_2',
        'town',
        'upz',
        'neighborhood',
        'phone_number_1',
        'phone_number_2',
        'legal_representative_name',
        'legal_representative_doc_type',
        'legal_representative_doc_num',
        'operating_hours',
        'is_active',
        'latitude',
        'longitude',
    ];


    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(Checklist::class);
    }

    public function checklist_items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function checklist_item_answers(): HasMany
    {
        return $this->hasMany(ChecklistItemAnswer::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function cleaningImplements(): HasMany
    {
        return $this->hasMany(CleaningImplement::class);
    }

    public function cleaningRecords(): HasMany
    {
        return $this->hasMany(CleaningRecord::class);
    }

    public function clientSatisfactionEvaluations(): HasMany
    {
        return $this->hasMany(ClientSatisfactionEvaluation::class);
    }

    public function clientPqrsRecords(): HasMany
    {
        return $this->hasMany(ClientPqrsRecord::class);
    }

    public function desinfectants(): HasMany
    {
        return $this->hasMany(Desinfectant::class);
    }

    public function dispatches(): HasMany
    {
        return $this->hasMany(Dispatch::class);
    }

    public function dispenseRecords(): HasMany
    {
        return $this->hasMany(DispenseRecord::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
    public function document_categories(): HasMany
    {
        return $this->hasMany(DocumentCategory::class);
    }

    public function environmentalRecords(): HasMany
    {
        return $this->hasMany(EnvironmentalRecord::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function externalOrders(): HasMany
    {
        return $this->hasMany(ExternalOrder::class);
    }

    public function externalOrderTeamCandidates(): HasMany
    {
        return $this->hasMany(ExternalOrderTeamCandidate::class, 'team_id');
    }

    public function improvement_plans(): HasMany
    {
        return $this->hasMany(ImprovementPlan::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function managementIndicators(): BelongsToMany
    {
        return $this->belongsToMany(ManagementIndicator::class, 'management_indicator_team')
            ->using(ManagementIndicatorTeam::class)
            ->withPivot([
                'role_id',
                'periodicity',
                'indicator_goal',
            ])
            ->withTimestamps();
    }

    public function minutesIvcSections(): HasMany
    {
        return $this->hasMany(MinutesIvcSection::class);
    }

    public function missing_products(): HasMany
    {
        return $this->hasMany(MissingProduct::class);
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
    
    public function processes(): HasMany
    {
        return $this->hasMany(Process::class);
    }

    public function productPrices(): HasMany
    {
        return $this->hasMany(TeamProductPrice::class);
    }

    public function productReceptions(): HasMany
    {
        return $this->hasMany(ProductReception::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
    
    public function qualityGoals(): HasMany
    {
        return $this->hasMany(QualityGoal::class);
    }


    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function resiudos(): HasMany
    {
        return $this->hasMany(Residuo::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(TenantSetting::class);
    }

    public function stablishmentAreas(): HasMany
    {
        return $this->hasMany(StablishmentArea::class);
    }

    public function spillCleanups(): HasMany
    {
        return $this->hasMany(SpillCleanup::class);
    }
    /**
     * Recupera el valor de un setting dado su ID.
     */
    public function getSettingValue(int $settingId): ?string
    {
        $setting = $this->settings()
            ->where('setting_id', $settingId)
            ->first();

        return $setting?->value;
    }

    /**
     * Recupera el data (JSON) de un setting dado su ID.
     */
    public function getSettingData(int $settingId): ?array
    {
        $setting = $this->settings()
            ->where('setting_id', $settingId)
            ->first();

        return $setting?->data;
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function training_categories(): HasMany
    {
        return $this->hasMany(TrainingCategory::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }

    /**
     * Relación: Un equipo tiene muchos usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id')->withPivot('is_owner');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(WasteGenerationReport::class);
    }

    public function wastes(): HasMany
    {
        return $this->hasMany(Waste::class);
    }
}
