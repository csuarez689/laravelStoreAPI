Hola {{$user->name}}
Gracias por registrarte. Por favor verifica tu email usando este link:
{{route('verify',$user->verification_token)}}