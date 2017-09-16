<?php include('stubs/header.php'); ?>
<form method="post">
	<input aria-label="username"
		type="text"
		name="username"
		placeholder="username"
		id="username"
	/>
	<input aria-label="password"
		type="password"
		name="password"
		placeholder="password"
	/>
	<button>Log In</button>
</form>
<script>
document.getElementById('username').focus();
</script>
<?php include('stubs/footer.php'); ?>
