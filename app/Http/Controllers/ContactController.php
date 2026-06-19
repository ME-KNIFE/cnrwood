<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

/**
 * Phase 7C — Public contact form controller.
 *
 * Scope:
 *   - Reads/writes ONLY the existing contact_messages table.
 *   - NO email sending, NO file upload, NO captcha (honeypot + throttle suffice).
 */
class ContactController extends Controller
{
    public function create()
    {
        return view('public.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'string', 'email:rfc', 'max:160'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:4000'],
            // Honeypot — bots fill every input.
            'website' => ['nullable', 'size:0'],
        ], [
            'email.email'   => 'Geçerli bir e-posta adresi giriniz.',
            'website.size'  => 'Spam kontrolü başarısız.',
        ]);

        ContactMessage::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone']   ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'is_read' => false,
        ]);

        return redirect()
            ->route('public.contact')
            ->with('contact_success', true);
    }
}
