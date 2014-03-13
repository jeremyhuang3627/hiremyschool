<div id="login-box" class="login popup"> 
	<a href="#" class="close" id="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<form method="post" action="index.php" class="login menu-form" id="login-form"> 
		<div> 
			<div class="form-field">
				<label for="email">Email:</label><br />
				<input type="text" name="email" id="email" class="text_box"/> 
				<br />
			</div> 
			<div class="form-field">
				<label for="password">Password:</label><br />
				<input type="password" name="password" id="password" class="text_box" /> 
				<br />
			</div>
			<input type="submit" id="submit" value="Submit" class="button"/>
		</div> 
	</form> 
	<a href="reset_pass_email.php" id="forget-pass-link" ><img src="img/bolt.png" />Oops, I forget my password.</a>
</div> 

<div id="signup-box" class="signup popup"> 
	<a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<form method="post" action="createAccount.php" class="signup menu-form" id="signup-form"> 
		<div>
			<div class="form-field">
				<label for="email">Enter your college email:</label><br />
				<input type="text" name="email" id="email" class="text_box"/> 
				<br />
			</div> 
		</div>
		<input type="submit" id="signup-submit" value="Sign up" class="button" /> 
	</form> 
</div> 

<div class="email-repeat popup">
	<a href="#" class="close-popup"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<span style="font-size:12px;">This email has already been registered.</span>
</div>


<div class="error-popup popup">
	<a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<img src="img/mail.png" id="mail-image" style="position:relative;left:110px;"/><br /><br />
	<span style="font-size:12px;">Oops, this is embarrasing.<br /><br />An error has occured.</span>
</div>

<div class="notification popup">
	<a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<img src="img/email.png" id="mail-image" style="position:relative;left:110px;"/><br /><br />
	<span style="font-size:12px;">A verification email has been sent to your email address.<br /><br />Please click the link to activate your account</span>
</div> 


<div class="success popup">
	<a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<span style="font-size:12px;">Congratulation! Your account has been activated.</span>
</div> 


<div id="add-item-box" class="add-item popup"> 
	<a href="#" class="close" id="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<form method="post" action="store.php?type=1" class="add-item menu-form" id="add-item-form"> 
		<div> 
			<div class="form-field">
				<label for="service">What kind of service do you want to offer? </label><br />
				<input type="text" name="service" id="service" class="text_box add-item-text-box" placeholder="eg. Dormitory cleaning/Math tutoring/Essay Editing"/> 
				<br />
			</div> 
			<div class="form-field">
				<label for="price">How would you charge it?</label><br />
				<input type="text" name="price" id="price" class="text_box add-item-text-box" placeholder="eg. $10 per hour" /> 
				<br />
			</div>
			<div class="form-field">
				<label for="description">Service description</label><br />
				<textarea name="description" id="description" class="text_box add-item-text-box" placeholder="Enter your service description here"></textarea>
				<br />
			</div>
			<div id="add-item-submit"> 
			<input type="submit" id="add-item-submit-button" value="Add item" class="button"/>
		    </div>
		</div> 
	</form> 
</div> 

<div id="helpbox" class="help popup"> 
	<a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<img src="img/light_bulb.png" />
	<div id="intro">Galvea.com is a service-based e-commerce platform<img src="img/my_store.png" /> built for college students. Its founder believes everyone has something to sell. 
		If you are good at writing essays, you can edit someone's paper for a small fee. 
		If you are a math genius<img src="img/pie_division.png" /> , you can help someone with their math homework. Or you simply enjoys doing household chores<img src="img/broom.png" />, then you can clean someone's room for money... 
		At Galvea.com, you can think of thousands of ways to make money <img src="img/coins.png" /> and help your fellow school mates. All you need to do is to sign up, and post a small ad here. You will NOT <img src="img/no_fee.png" /> be charged any commission fees because Galvea.com is TOTALLY FREE. And you can control how you will be paid, be it by cash, by credits or with some delicious homemade cookies! Sounds exciting ? Be your own boss today by signing up <img src="img/sign_up_big.png" /> at Galvea.com!</div>
</div>

<div class="login-review popup">
	<a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<span style="font-size:12px;">Oops, looks like you need to log in to post a review!</span>
</div> 



<div id="confirm-dialog" hidden> 
	<span>Do you really want to delete this picture?</span> 
</div>

<div class="mask-review"> 
</div> 