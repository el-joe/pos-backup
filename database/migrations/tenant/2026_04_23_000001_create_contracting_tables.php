<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Contracting / Construction ERP module — full schema.
 *
 * Tables are prefixed where there is a collision risk with existing tenant
 * tables (purchase_requests, purchase_orders) and left unprefixed otherwise.
 * All tables use softDeletes + timestamps.
 */
return new class extends Migration {
    public function up(): void
    {
        /* ------------------------------------------------------------------ */
        /* Accounting backbone                                                */
        /* ------------------------------------------------------------------ */
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['type', 'is_active']);
        });

        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        /* ------------------------------------------------------------------ */
        /* Tenders / BOQ                                                       */
        /* ------------------------------------------------------------------ */
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->foreignId('client_id')->nullable(); // users table (customer)
            $table->date('submission_date')->nullable();
            $table->date('opening_date')->nullable();
            $table->decimal('estimated_value', 15, 2)->default(0);
            $table->enum('status', ['pending', 'submitted', 'won', 'lost', 'cancelled'])->default('pending');
            $table->text('scope_of_work')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
        });

        Schema::create('boqs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->nullable();
            $table->foreignId('tender_id')->nullable()->constrained('tenders')->nullOnDelete();
            $table->foreignId('project_id')->nullable(); // set after projects created
            $table->enum('type', ['estimated', 'actual'])->default('estimated');
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_value', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('boq_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boq_id')->constrained('boqs')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('boq_items')->nullOnDelete();
            $table->string('item_code', 50)->nullable();
            $table->text('description');
            $table->string('unit', 20)->nullable();
            $table->decimal('estimated_quantity', 15, 3)->default(0);
            $table->decimal('unit_rate', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('boq_id');
        });

        /* ------------------------------------------------------------------ */
        /* Projects / WBS / Contracts                                          */
        /* ------------------------------------------------------------------ */
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->foreignId('client_id')->nullable();
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            $table->foreignId('tender_id')->nullable()->constrained('tenders')->nullOnDelete();
            $table->enum('status', ['planning', 'active', 'completed', 'on_hold', 'cancelled'])->default('planning');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('total_budget', 15, 2)->default(0);
            $table->decimal('total_contract_value', 15, 2)->default(0);
            $table->text('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
        });

        Schema::table('boqs', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->nullOnDelete();
        });

        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('project_tasks')->nullOnDelete();
            $table->foreignId('boq_item_id')->nullable()->constrained('boq_items')->nullOnDelete();
            $table->string('name');
            $table->decimal('weight_percentage', 5, 2)->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->nullableMorphs('party'); // owner (client/User) or subcontractor (User/Supplier)
            $table->enum('type', ['owner', 'subcontractor']);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('retention_percentage', 5, 2)->default(0);
            $table->decimal('advance_payment_amount', 15, 2)->default(0);
            $table->decimal('taxes_percentage', 5, 2)->default(0);
            $table->enum('status', ['draft', 'active', 'completed', 'terminated'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id', 'type']);
        });

        /* ------------------------------------------------------------------ */
        /* Extract engine                                                      */
        /* ------------------------------------------------------------------ */
        Schema::create('extracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();
            $table->unsignedInteger('extract_number');
            $table->enum('type', ['progress', 'final'])->default('progress');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->decimal('gross_amount', 15, 2)->default(0);
            $table->decimal('deductions_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'pending', 'approved', 'paid', 'rejected'])->default('draft');
            $table->date('approved_at')->nullable();
            $table->date('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['contract_id', 'extract_number']);
            $table->index('status');
        });

        Schema::create('extract_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extract_id')->constrained('extracts')->cascadeOnDelete();
            $table->foreignId('boq_item_id')->nullable()->constrained('boq_items')->nullOnDelete();
            $table->decimal('previous_quantity', 15, 3)->default(0);
            $table->decimal('current_quantity', 15, 3)->default(0);
            $table->decimal('total_quantity_to_date', 15, 3)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_line_amount', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('extract_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extract_id')->constrained('extracts')->cascadeOnDelete();
            $table->foreignId('account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->enum('type', ['retention', 'tax', 'advance', 'penalty', 'other']);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        /* ------------------------------------------------------------------ */
        /* Supplier quotations on BOQ                                          */
        /* ------------------------------------------------------------------ */
        Schema::create('supplier_quotations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('boq_id')->nullable()->constrained('boqs')->nullOnDelete();
            $table->foreignId('tender_id')->nullable()->constrained('tenders')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable(); // users table (supplier)
            $table->date('quotation_date')->nullable();
            $table->date('valid_until')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('supplier_quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_quotation_id')->constrained('supplier_quotations')->cascadeOnDelete();
            $table->foreignId('boq_item_id')->nullable()->constrained('boq_items')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('unit', 20)->nullable();
            $table->decimal('quantity', 15, 3)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->timestamps();
        });

        /* ------------------------------------------------------------------ */
        /* Materials catalog + warehouses + inventory                          */
        /* ------------------------------------------------------------------ */
        Schema::create('construction_items', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->foreignId('category_id')->nullable();
            $table->string('unit', 20)->nullable();
            $table->boolean('is_inventory_tracked')->default(true);
            $table->decimal('standard_cost', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->foreignId('branch_id')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('manager_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('contracting_purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('requested_by')->nullable(); // admins
            $table->date('required_date')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'fulfilled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
        });

        Schema::create('contracting_purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained('contracting_purchase_requests')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('construction_items')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('unit', 20)->nullable();
            $table->decimal('quantity', 15, 3)->default(0);
            $table->decimal('estimated_unit_price', 15, 2)->default(0);
            $table->decimal('estimated_total', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('contracting_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('purchase_request_id')->nullable()->constrained('contracting_purchase_requests')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->date('order_date')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'approved', 'partially_received', 'received', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
        });

        Schema::create('contracting_purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('contracting_purchase_orders')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('construction_items')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('unit', 20)->nullable();
            $table->decimal('quantity', 15, 3)->default(0);
            $table->decimal('received_quantity', 15, 3)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('item_id')->constrained('construction_items')->restrictOnDelete();
            $table->enum('type', ['in', 'out', 'transfer', 'adjustment']);
            $table->decimal('quantity', 15, 3);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->date('transaction_date');
            $table->nullableMorphs('source'); // PO, PR, extract, adjustment, transfer
            $table->foreignId('created_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['item_id', 'transaction_date']);
            $table->index(['project_id', 'transaction_date']);
            $table->index('type');
        });

        /* ------------------------------------------------------------------ */
        /* Journal entries (event-driven accounting)                           */
        /* ------------------------------------------------------------------ */
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->nullable();
            $table->nullableMorphs('referenceable'); // extract, inventory_transaction, PO, payment, etc.
            $table->date('date');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'posted', 'reversed'])->default('draft');
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->foreignId('posted_by')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['date', 'status']);
        });

        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('journal_entries')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('chart_of_accounts')->restrictOnDelete();
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->index(['account_id', 'project_id']);
        });

        /* ------------------------------------------------------------------ */
        /* Labor & Equipment                                                   */
        /* ------------------------------------------------------------------ */
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->enum('type', ['staff', 'labor'])->default('labor');
            $table->string('national_id')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->decimal('monthly_salary', 10, 2)->default(0);
            $table->date('hire_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('labor_timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained('workers')->restrictOnDelete();
            $table->foreignId('project_task_id')->nullable()->constrained('project_tasks')->nullOnDelete();
            $table->date('date');
            $table->decimal('hours_worked', 5, 2)->default(0);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['project_id', 'date']);
        });

        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->enum('type', ['owned', 'rented'])->default('owned');
            $table->string('asset_tag')->nullable();
            $table->decimal('hourly_cost_rate', 10, 2)->default(0);
            $table->decimal('daily_cost_rate', 10, 2)->default(0);
            $table->string('supplier_name')->nullable(); // for rented
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('equipment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->restrictOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_task_id')->nullable()->constrained('project_tasks')->nullOnDelete();
            $table->date('date');
            $table->decimal('hours_used', 5, 2)->default(0);
            $table->decimal('fuel_consumed', 10, 2)->default(0);
            $table->decimal('fuel_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->string('operator_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['project_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_logs');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('labor_timesheets');
        Schema::dropIfExists('workers');
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('contracting_purchase_order_items');
        Schema::dropIfExists('contracting_purchase_orders');
        Schema::dropIfExists('contracting_purchase_request_items');
        Schema::dropIfExists('contracting_purchase_requests');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('construction_items');
        Schema::dropIfExists('supplier_quotation_items');
        Schema::dropIfExists('supplier_quotations');
        Schema::dropIfExists('extract_deductions');
        Schema::dropIfExists('extract_lines');
        Schema::dropIfExists('extracts');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('project_tasks');
        Schema::table('boqs', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
        });
        Schema::dropIfExists('projects');
        Schema::dropIfExists('boq_items');
        Schema::dropIfExists('boqs');
        Schema::dropIfExists('tenders');
        Schema::dropIfExists('cost_centers');
        Schema::dropIfExists('chart_of_accounts');
    }
};
