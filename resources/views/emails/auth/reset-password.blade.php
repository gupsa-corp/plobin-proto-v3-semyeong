@component('mail::message')
# 비밀번호 재설정

안녕하세요!

계정에서 비밀번호 재설정 요청을 받았습니다. 아래 버튼을 클릭하여 비밀번호를 재설정하세요.

@component('mail::button', ['url' => $url])
비밀번호 재설정
@endcomponent

**이 링크는 {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }}분 후에 만료됩니다.**

만약 비밀번호 재설정을 요청하지 않으셨다면, 이 이메일을 무시하셔도 됩니다.

감사합니다,<br>
{{ config('app.name') }} 팀

@slot('subcopy')
"비밀번호 재설정" 버튼이 작동하지 않으면 아래 URL을 복사하여 웹 브라우저에 붙여넣으세요:
<span class="break-all">[{{ $url }}]({{ $url }})</span>
@endslot
@endcomponent