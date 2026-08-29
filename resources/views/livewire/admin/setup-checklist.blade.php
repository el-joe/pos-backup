@php
    $isAr = app()->getLocale() === 'ar';

    $labels = [
        'business_name' => $isAr ? 'اسم النشاط التجاري' : 'Business name is set',
        'logo' => $isAr ? 'رفع الشعار' : 'Logo uploaded',
        'branch' => $isAr ? 'إضافة فرع واحد على الأقل' : 'At least 1 branch created',
        'product' => $isAr ? 'إضافة منتج واحد على الأقل' : 'At least 1 product added',
        'payment_method' => $isAr ? 'إعداد طريقة دفع' : 'Payment method configured',
        'tax_number' => $isAr ? 'تحديد الرقم الضريبي (اختياري)' : 'Tax number set (optional)',
    ];
@endphp

@if(!$dismissed)
<div class="setup-checklist-widget" style="position:fixed;bottom:1.5rem;{{ $isAr ? 'left' : 'right' }}:1.5rem;z-index:1050;width:320px;max-width:90vw;">
    <div class="card shadow-lg border-0" style="border-radius:1rem;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">
                    @if($this->isComplete)
                        <i class="fa fa-circle-check text-success me-1"></i> {{ $isAr ? 'اكتمل الإعداد!' : 'Setup Complete!' }}
                    @else
                        {{ $isAr ? 'خطوات الإعداد' : 'Setup Progress' }}: {{ $this->completedCount }}/{{ $this->totalCount }} ✓
                    @endif
                </h6>
                <button type="button" class="btn-close btn-sm" wire:click="dismiss" aria-label="Close"></button>
            </div>

            <div class="progress mb-3" style="height:6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $this->totalCount > 0 ? round(($this->completedCount / $this->totalCount) * 100) : 0 }}%"></div>
            </div>

            <ul class="list-unstyled mb-3">
                @foreach($items as $item)
                    <li class="d-flex align-items-center mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center me-2"
                              style="width:18px;height:18px;border-radius:50%;background:{{ $item['done'] ? '#198754' : '#e9ecef' }};flex-shrink:0;">
                            @if($item['done'])
                                <i class="fa fa-check text-white" style="font-size:.6rem;"></i>
                            @endif
                        </span>
                        <small class="{{ $item['done'] ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $labels[$item['key']] }}
                        </small>
                    </li>
                @endforeach
            </ul>

            @if($this->isComplete)
                <button type="button" class="btn btn-success btn-sm w-100" wire:click="dismiss">
                    {{ $isAr ? 'إغلاق' : 'Dismiss' }}
                </button>
            @else
                <a href="{{ route('admin.settings') }}" class="btn btn-outline-primary btn-sm w-100">
                    {{ $isAr ? 'إكمال الإعداد' : 'Complete Setup' }}
                </a>
            @endif
        </div>
    </div>
</div>
@endif
