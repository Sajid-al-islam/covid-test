@component('mail::message')
# Vaccination Scheduled

Dear {{ $registration->user?->name }},

We are pleased to inform you that your COVID-19 vaccination has been scheduled.

- **Vaccine Center:** {{ $registration?->vaccineCenter?->name }}
- **Adress:** {{ $registration?->vaccineCenter?->address }}
@if (!empty($registration->scheduled_date))
- **Scheduled Date:** {{ \Carbon\Carbon::parse($registration->scheduled_date)->format('F j, Y') }}
@endif

Please make sure to bring your NID card to the vaccination center. If you have any questions or need further assistance, feel free to contact us.

Thank you for registering with us!

Regards,<br>
{{ config('app.name') }}
@endcomponent
