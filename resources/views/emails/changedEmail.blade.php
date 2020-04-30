Hola {{$user->name}}
Has cambiado tu correo electronico. Por favor verifica tu nuevo email usando este link:
{{route('verify',$user->verification_token)}}