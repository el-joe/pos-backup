<?php

use App\Enums\EmployeeStatusEnum;
use App\Enums\EmploymentTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('national_id')->nullable();
            $table->string('passport_number')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();

            // Employment Details
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('employment_type')->default(EmploymentTypeEnum::FULL_TIME->value);
            $table->date('joining_date')->nullable();
            $table->date('probation_end_date')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->date('resignation_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->text('termination_reason')->nullable();
            $table->string('status')->default(EmployeeStatusEnum::ACTIVE->value);

            // Bank Details
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('swift_code')->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            // Profile
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->json('social_links')->nullable();

            // User Account Link
            $table->foreignId('user_id')->nullable()->constrained('admins')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });

        if (Schema::hasTable('departments') && Schema::hasColumn('departments', 'manager_id')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->foreign('manager_id')
                    ->references('id')
                    ->on('employees')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
