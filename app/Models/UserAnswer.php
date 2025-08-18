<?php

namespace App\Models;

use App\Models\Quality\Training\AssessmentAttempt;
use App\Models\Quality\Training\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id',
        'question_option_id',
        'assessment_attempt_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function question_option(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class);
    }

    public function assessmentAttempt(): BelongsTo
    {
        return $this->belongsTo(AssessmentAttempt::class);
    }
}
