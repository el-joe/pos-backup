<?php

namespace App\Services\Hrm;

/**
 * Computes statutory social-insurance and income-tax withholding for payroll.
 *
 * Rates/brackets are NOT hard-coded — they are read from tenant settings so each
 * tenant (different country/jurisdiction) can configure their own figures:
 *
 *  - payroll_social_insurance_employee_rate (percent, e.g. 11 for 11%)
 *  - payroll_social_insurance_employer_rate (percent, e.g. 18.75 for 18.75%)
 *  - payroll_social_insurance_base (optional cap on the salary base subject to
 *    social insurance; 0/empty = uncapped)
 *  - payroll_income_tax_brackets: JSON array of {"up_to": number|null, "rate": number}
 *    ordered ascending by "up_to", evaluated as a progressive bracket table.
 *    A null "up_to" on the last bracket means "and above". Example:
 *    [{"up_to":1000,"rate":0},{"up_to":5000,"rate":10},{"up_to":null,"rate":20}]
 */
class StatutoryDeductionService
{
    public function socialInsuranceEmployee(float $insurableSalary): float
    {
        $rate = (float) tenantSetting('payroll_social_insurance_employee_rate', 0);
        return round($this->cappedBase($insurableSalary) * $rate / 100, 2);
    }

    public function socialInsuranceEmployer(float $insurableSalary): float
    {
        $rate = (float) tenantSetting('payroll_social_insurance_employer_rate', 0);
        return round($this->cappedBase($insurableSalary) * $rate / 100, 2);
    }

    protected function cappedBase(float $insurableSalary): float
    {
        $cap = (float) tenantSetting('payroll_social_insurance_base', 0);
        if ($cap > 0) {
            return min($insurableSalary, $cap);
        }
        return $insurableSalary;
    }

    public function incomeTax(float $taxableIncome): float
    {
        $brackets = $this->brackets();
        if (empty($brackets)) {
            return 0.0;
        }

        $tax = 0.0;
        $lowerBound = 0.0;

        foreach ($brackets as $bracket) {
            $upTo = $bracket['up_to'] ?? null;
            $rate = (float) ($bracket['rate'] ?? 0);

            $sliceTop = $upTo === null ? $taxableIncome : min((float) $upTo, $taxableIncome);
            $sliceAmount = max(0.0, $sliceTop - $lowerBound);

            if ($sliceAmount > 0) {
                $tax += $sliceAmount * $rate / 100;
            }

            if ($upTo === null || $taxableIncome <= $upTo) {
                break;
            }

            $lowerBound = (float) $upTo;
        }

        return round(max(0.0, $tax), 2);
    }

    protected function brackets(): array
    {
        $raw = tenantSetting('payroll_income_tax_brackets', '[]');

        if (is_array($raw)) {
            return $raw;
        }

        $decoded = json_decode((string) $raw, true);
        return is_array($decoded) ? $decoded : [];
    }
}
