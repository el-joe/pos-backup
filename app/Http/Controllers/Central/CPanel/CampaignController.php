<?php

namespace App\Http\Controllers\Central\CPanel;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailCampaignJob;
use App\Mail\CampaignMail;
use App\Models\EmailCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CampaignController extends Controller
{
    public function create()
    {
        return view('central.cpanel.newsletter.campaign-form', ['campaign' => null]);
    }

    public function edit(int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);

        return view('central.cpanel.newsletter.campaign-form', ['campaign' => $campaign]);
    }

    public function store(Request $request)
    {
        $campaign = $this->save($request, new EmailCampaign());

        return redirect()
            ->route('cpanel.newsletter.campaigns.edit', ['id' => $campaign->id])
            ->with('success', 'Campaign saved as draft');
    }

    public function update(Request $request, int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        $this->save($request, $campaign);

        return redirect()
            ->route('cpanel.newsletter.campaigns.edit', ['id' => $campaign->id])
            ->with('success', 'Campaign saved successfully');
    }

    protected function save(Request $request, EmailCampaign $campaign): EmailCampaign
    {
        $validated = $request->validate([
            'subject_en' => 'required|string|max:255',
            'subject_ar' => 'nullable|string|max:255',
            'body_en' => 'required|string',
            'body_ar' => 'nullable|string',
            'recipient_type' => 'required|in:all,active_only,manual',
            'manual_emails' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
        ]);

        if (empty($validated['scheduled_at'])) {
            $validated['scheduled_at'] = null;
        }

        $campaign->fill($validated);

        if (!$campaign->exists) {
            $campaign->status = 'draft';
            $campaign->created_by = admin()?->id;
        }

        $campaign->save();

        return $campaign;
    }

    public function sendPreview(Request $request, int $id)
    {
        $validated = $request->validate([
            'preview_email' => 'required|email|max:255',
        ]);

        $campaign = EmailCampaign::findOrFail($id);

        Mail::to($validated['preview_email'])->send(new CampaignMail($campaign, 'en', $validated['preview_email']));

        return redirect()
            ->route('cpanel.newsletter.campaigns.edit', ['id' => $campaign->id])
            ->with('success', 'Preview email sent to ' . $validated['preview_email']);
    }

    public function send(int $id)
    {
        $campaign = EmailCampaign::findOrFail($id);

        if (in_array($campaign->status, ['sending', 'sent'], true)) {
            return redirect()
                ->route('cpanel.newsletter.campaigns.edit', ['id' => $campaign->id])
                ->with('error', 'This campaign has already been sent or is currently sending.');
        }

        $campaign->update(['status' => 'queued']);

        SendEmailCampaignJob::dispatch($campaign->id);

        return redirect()
            ->route('cpanel.newsletter.campaigns.edit', ['id' => $campaign->id])
            ->with('success', 'Campaign has been queued for sending');
    }
}
