<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CheckoutRequest extends FormRequest {
    public function authorize(): bool { return $this->user()?->role==='user'; }
    public function rules(): array { return ['recipient_name'=>['required','string','max:255'],'phone'=>['required','regex:/^0[0-9]{9,10}$/'],'address'=>['required','string','max:2000'],'payment_method'=>['required','in:cod'],'checkout_token'=>['required','uuid']]; }
}
