<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('registrations')->whereNull('deleted_at')],
            'phone' => ['required', 'numeric', Rule::unique('registrations')->whereNull('deleted_at')],
            'company_industry' => ['nullable'],
            'company_industry_input' => ['required_if:company_industry,Other'],
            'company_name' => ['required'],
            'office' => ['required'],
            'office_input' => ['nullable_if:office,Other'],
            'company_phone' => ['nullable', 'numeric'],
            'company_email' => ['nullable', 'email'],
            'company_address' => ['required'],
            'workshop_question' => ['nullable'],

            'will_attend' => ['required'],
            // 'membership' => ['required'],
            // 'image' => ['required', 'image', 'max:2042'],

            'event_info_source' => ['nullable'],
            'event_info_source_input' => ['nullable_if:event_info_source,Other'],
            'attendance_lateness' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Masukkan email yang valid',
            'phone.required' => 'Nomor Handphone (WA) harus diisi',
            'phone.numeric' => 'Nomor Handphone (WA) harus berupa angka',
            'company_industry.required' => 'Jenis Industry harus diisi',
            'company_industry_input.required_if' => 'Jika memilih jenis industri "Yang Lain", harap mengisi input',
            'company_name.required' => 'Nama Perusahaan harus diisi',
            'office.required' => 'Jabatan harus diisi',
            'office_input.required_if' => 'Jika memilih jabatan "Yang Lain", harap mengisi input',
            'company_phone.required' => 'Nomor Telepon Perusahaan harus diisi',
            'company_phone.numeric' => 'Nomor Telepon Perusahaan harus berupa angka',
            'company_email.required' => 'Email Perusahaan harus diisi',
            'company_email.email' => 'Masukan email yang valid',
            'company_address.required' => 'Alamat Perusahaan harus diisi',
            'workshop_question.required' => 'Pertanyaan seputar tema harus diisi',

            'will_attend.required' => 'Kehadiran harus diisi',
            'membership.required' => 'Keanggotaan harus diisi',
            'image.required' => 'Gambar tidak boleh kosong',

            'event_info_source.required' => 'Pertanyaan ini harus diisi',
            'event_info_source_input.required_if' => 'Jika memilih "Yang Lain", harap mengisi input',
            'attendance_lateness.required' => 'Pertanyaan ini harus diisi',

            'email.unique' => 'Email telah digunakan, harap gunakan email lain.',
            'phone.unique' => 'Nomor Handphone (WA) telah digunakan, harap gunakan nomor telepon lain.'
        ];
    }
}
