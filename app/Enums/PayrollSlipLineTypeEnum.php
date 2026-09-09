<?php

namespace App\Enums;

enum PayrollSlipLineTypeEnum: string
{
    case EARNING = 'earning';
    case DEDUCTION = 'deduction';
    case BASIC = 'basic';
    case OVERTIME = 'overtime';
    case BONUS = 'bonus';
    case ALLOWANCE = 'allowance';
    case UNPAID_LEAVE_DEDUCTION = 'unpaid_leave_deduction';
    case ABSENCE_DEDUCTION = 'absence_deduction';
    case COMMISSION = 'commission';
    case LOAN_REPAYMENT = 'loan_repayment';
    case ADVANCE_DEDUCTION = 'advance_deduction';
    case PENALTY_DEDUCTION = 'penalty_deduction';
    case SOCIAL_INSURANCE_EMPLOYEE = 'social_insurance_employee';
    case SOCIAL_INSURANCE_EMPLOYER = 'social_insurance_employer';
    case INCOME_TAX = 'income_tax';

    public function label(): string
    {
        return __('general.pages.hrm.types.' . $this->value);
    }

    /** Earning line types that add to gross pay. */
    public static function earningTypes(): array
    {
        return [self::BASIC, self::OVERTIME, self::BONUS, self::ALLOWANCE, self::COMMISSION];
    }

    /** Deduction line types that reduce net pay (employee-side only). */
    public static function deductionTypes(): array
    {
        return [
            self::UNPAID_LEAVE_DEDUCTION,
            self::ABSENCE_DEDUCTION,
            self::LOAN_REPAYMENT,
            self::ADVANCE_DEDUCTION,
            self::PENALTY_DEDUCTION,
            self::SOCIAL_INSURANCE_EMPLOYEE,
            self::INCOME_TAX,
        ];
    }

    /** Employer-borne cost line types (do not reduce employee net pay). */
    public static function employerCostTypes(): array
    {
        return [self::SOCIAL_INSURANCE_EMPLOYER];
    }
}
