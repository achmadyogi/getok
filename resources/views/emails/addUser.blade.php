Dear,<br>
{{$user->email}}<br><br>
<div style="padding: 10px 15px 10px 15px; text-align: center; background-color: lightgrey;">
	<h2 style="color: grey; text-align: center;">GETOK COLABORATION</h2>
</div>

<br>
You have been inform to collaborate with Getok. Please
activate your account to continue.<br><br>

<div style="text-align: center;">
	<h3 style="cursor: pointer;"><a href="<?php echo url("/account-activation/{$user->verification_code}"); ?>" style="text-decoration: none;"><button class="button-default">Account Activation</button></a></h3>
</div>

Regards,<br>
{{ config('app.name') }}
<hr>

If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <?php echo url("/account-activation/{$user->verification_code}"); ?>

<style type="text/css">
	.button-default{
		border-radius: 3px;
		background-color: #226dc9;
		padding: 5px 10px 5px 10px;
		color: white;
		border: none;
	}

	.button-default:hover {
		background-color: #1e5fb0;
		box-shadow: 0 4px 8px 0 rgba(225, 225, 225, 0.2), 0 6px 20px 0 rgba(225, 225,225, 0.19);
	}
</style>
