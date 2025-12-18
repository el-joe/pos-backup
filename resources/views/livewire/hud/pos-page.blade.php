<div id="wizardLayout1" class="mb-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="nav-wizards-container mb-3">
                <nav class="nav nav-wizards-1 mb-2">
                    <div class="nav-item col" wire:click="$set('step', 1)">
                        <a class="nav-link {{ $step > 0 ? 'completed' : '' }}" href="javascript:void(0);">
                            <div class="nav-no">1</div>
                            <div class="nav-text">{{ __('general.pages.pos-page.order_details') }}</div>
                        </a>
                    </div>
                    <div class="nav-item col" wire:click="$set('step', 2)">
                        <a class="nav-link {{ $step > 1 ? 'completed' : '' }}" href="javascript:void(0);">
                            <div class="nav-no">2</div>
                            <div class="nav-text">{{ __('general.pages.pos-page.order_products') }}</div>
                        </a>
                    </div>
                </nav>
            </div>

            <div class="pos card shadow-sm" id="pos">
                @if($step == 1)
                <div class="card-body row g-3">
                    <div class="col-sm-4">
                        <label for="branchSelect" class="fw-semibold">{{ __('general.pages.pos-page.branch') }}:</label>
                        <div class="d-flex">
                            @if(admin()->branch_id == null)
                                <select class="form-select" id="branchSelect" wire:model.live="data.branch_id">
                                    <option value="">-- {{ __('general.pages.pos-page.branch') }} --</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->name }} @if($branch->phone) - {{ $branch->phone }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', null)">
                                    +
                                </button>

                                @error('data.branch_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            @else
                                <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <label for="customerSelect" class="fw-semibold">{{ __('general.pages.pos-page.customer') }}:</label>
                        <div class="d-flex">
                            <select class="form-select" id="customerSelect" wire:model="selectedCustomerId">
                                <option value="">-- {{ __('general.pages.pos-page.customer') }} --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name }} @if($customer->phone) - {{ $customer->phone }} @endif
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : null, type: 'customer'})">
                                +
                            </button>
                            @error('selectedCustomerId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <label for="orderDate" class="fw-semibold">{{ __('general.pages.pos-page.order_date') }}:</label>
                        <input type="date" class="form-control" id="orderDate" wire:model="data.order_date">
                        @error('data.order_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-3">
                        <label for="dueDate" class="fw-semibold">{{ __('general.pages.pos-page.due_date') }}:</label>
                        <input type="date" class="form-control" id="dueDate" wire:model="data.due_date">
                        @error('data.due_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-3">
                        <label for="invoiceNumber" class="fw-semibold">{{ __('general.pages.pos-page.invoice_number') }}:</label>
                        <input type="text" class="form-control" id="invoiceNumber" wire:model="data.invoice_number">
                        <small class="text-primary">{{ __('general.pages.pos-page.leave_blank_for_auto_generated') }}</small>
                    </div>

                    <div class="col-12">
                        <label for="paymentNote" class="fw-semibold">{{ __('general.pages.pos-page.payment_note') }}:</label>
                        <textarea class="form-control" id="paymentNote" wire:model="data.payment_note"></textarea>
                        @error('data.payment_note')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 text-end">
                        <a onclick="redirectTo('{{ route('admin.sales.index') }}')" href="javascript:" class="btn btn-secondary">
                            {{ __('general.pages.pos-page.orders_list') }} <i class="bi bi-list-ul fa-lg"></i>
                        </a>
                        <button type="button" class="btn btn-primary" wire:click="$set('step', 2)">
                            {{ __('general.pages.pos-page.next') }} <i class="bi bi-arrow-right-circle fa-lg"></i>
                        </button>
                    </div>
                </div>
                @elseif($step == 2)
                    @include('hud.pos-page.products')
                @endif

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>

			<a href="#" class="pos-mobile-sidebar-toggler" data-toggle-class="pos-mobile-sidebar-toggled" data-toggle-target="#pos">
                <i class="bi bi-bag"></i>
                <span class="badge bg-danger">{{ count($data['products'] ?? []) }}</span>
            </a>

            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="card shadow-sm mb-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" id="checkoutModalLabel">
                            <i class="fa fa-credit-card me-2"></i> {{ __('general.pages.pos-page.complete_payment') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <strong><i class="fa fa-money-bill me-1"></i> {{ __('general.pages.pos-page.order_total') }}:</strong> {{ currencyFormat($total, true) }}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('general.pages.pos-page.payment_method') }}</th>
                                        <th>{{ __('general.pages.pos-page.amount') }}</th>
                                        <th class="text-center" style="width: 70px;">{{ __('general.pages.pos-page.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payments as $index => $payment)
                                        <tr>
                                            <td>
                                                <select class="form-select" wire:model="payments.{{ $index }}.account_id">
                                                    <option value="">-- {{ __('general.pages.pos-page.payment_method') }} --</option>
                                                    @foreach ($selectedCustomer?->accounts ?? [] as $account)
                                                        <option value="{{ $account->id }}">
                                                            {{ $account->paymentMethod?->name }} - {{ $account->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" placeholder="{{ __('general.pages.pos-page.amount') }}"
                                                    wire:model="payments.{{ $index }}.amount" step="any" min="0" max="{{ $total }}">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" wire:click="removePayment({{ $index }})" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">
                                                <i class="fa fa-info-circle me-1"></i> {!! __('general.pages.pos-page.click_add_payment_to_get_started') !!}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-secondary" wire:click="addPayment">
                            <i class="fa fa-plus"></i> {{ __('general.pages.pos-page.add_payment') }}
                        </button>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> {{ __('general.pages.pos-page.cancel') }}
                        </button>
                        <button type="button" class="btn btn-success" wire:click="confirmPayment">
                            <i class="fa fa-check me-1"></i> {{ __('general.pages.pos-page.confirm_payment') }}
                        </button>
                    </div>

                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-pos fade" id="modalPosItem" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                @if($currentProduct)
                    <div class="card">
                        <div class="card-body p-0">
                            <a href="#" data-bs-dismiss="modal" class="btn-close position-absolute top-0 end-0 m-4"></a>
                            <div class="modal-pos-product">
                                <div class="modal-pos-product-img">
                                    <div class="img" style="background-image: url({{ $currentProduct->image_path }})"></div>
                                </div>
                                <div class="modal-pos-product-info">
                                    <div class="h4 mb-2">{{ $currentProduct->name }}</div>
                                    <div class="text-inverse text-opacity-50 mb-2">
                                        {{ $currentProduct->description }}
                                    </div>
                                    <div class="mb-2">
                                        <div class="fw-bold">{{ __('general.pages.pos-page.unit') }}:</div>
                                        <div class="option-list">
                                            @foreach($currentProduct->units() as $unit)
                                                <div class="option">
                                                    <input type="radio" id="unit-{{ $currentProduct->id }}-{{ $unit->id }}" name="size" class="option-input" wire:model.live="selectedUnitId" value="{{ $unit->id }}">
                                                    <label class="option-label" for="unit-{{ $currentProduct->id }}-{{ $unit->id }}">
                                                        <span class="option-text">{{ $unit->name }}</span>
                                                        @php $sellPrice = number_format($unit->stock($currentProduct->id,$this->data['branch_id']??null)?->sell_price ?? 0, 3); @endphp
                                                        <span class="option-price">{{ currencyFormat($sellPrice, true) }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <hr class="mx-n4">
                                    <div class="h4 mb-3">{{ __('general.pages.pos-page.quantity') }}</div>
                                    <div class="d-flex mb-3">
                                        <a href="javascript:" @if($selectedQuantity > 1) wire:click="$set('selectedQuantity', {{ $selectedQuantity }} - 1)" @endif class="btn btn-outline-theme"><i class="fa fa-minus"></i></a>
                                        <input type="text" class="form-control w-50px fw-bold mx-2 bg-inverse bg-opacity-15 border-0 text-center" wire:model="selectedQuantity" max="{{ $maxQuantity }}" readonly>
                                        <a href="javascript:" @if($selectedQuantity < $maxQuantity && $selectedQuantity != 0) wire:click="$set('selectedQuantity', {{ $selectedQuantity }} + 1)" @endif class="btn btn-outline-theme"><i class="fa fa-plus"></i></a>
                                    </div>
                                    <small class="text-danger">Max: {{ $maxQuantity ?? 0 }}</small>
                                    <hr class="mx-n4">
                                    <div class="row">
                                        <div class="col-4">
                                            <a href="#" class="btn btn-default h4 mb-0 d-block rounded-0 py-3" data-bs-dismiss="modal">{{ __('general.pages.pos-page.cancel') }}</a>
                                        </div>
                                        <div class="col-8">
                                            <a href="#" class="btn btn-theme d-flex justify-content-center align-items-center rounded-0 py-3 h4 m-0" wire:click="addToCart">{{ __('general.pages.pos-page.add_to_cart') }} <i class="bi bi-plus fa-2x ms-2 my-n3"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>


@push('scripts')
@livewire('admin.branches.branch-modal')
@livewire('admin.users.user-modal')

<script src="{{ asset('hud/assets/plugins/@highlightjs/cdn-assets/highlight.min.js') }}"></script>
<script src="{{ asset('hud/assets/js/demo/highlightjs.demo.js') }}"></script>
<script src="{{ asset('hud/assets/js/demo/sidebar-scrollspy.demo.js') }}"></script>
<script src="{{ asset('hud/assets/js/demo/pos-customer-order.demo.js') }}"></script>

<script>
    function redirectTo(url){
        // confirmation
        if(confirm(@json(__('general.pages.pos-page.confirm_leave')))){
            window.location.href = url;
        }
    }
</script>
@endpush
