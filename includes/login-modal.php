<!-- Login popup modal, using the same strategy as the mobile navbar above. -->
<span class="target-fix" id="login"></span>
<span class="target-fix" id="close-login"></span>
<div class="login-close">
	<div class="login-overlay">
		<div class="login-box">
			<div class="padding-login-form">
				<!-- the .button-closelogin class forces the 'x' icon to float right,
				this makes it render in a way that's familiar to users -->
				<h3>Log In<span class="button-closelogin"><a href="#close-login">&times;</a></span></h3>
				<label for="username">Username:</label>
				<input class="form-control" type="text" id="username">
				<label for="password">Password:</label>
				<input class="form-control" type="password" id="password">
				<br/>
				<button type="submit" class="btn btn-secondary">Go</button>
			</div>
		</div>
	</div>
</div>