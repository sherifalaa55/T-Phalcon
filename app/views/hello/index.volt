<!--
	- View
	@ Called by the HelloController / indexAction
-->
<div class="container">

	<div class="row">
		<div class="col-md-3">
			<div class="spacer30"></div>

			<div class="text-right">
				{{ image('public/assets/img/logo.png', 'class': 'logo') }}
				<hr>
			</div>
	
			<div class="list-group text-right">			
				<a href="http://docs.phalconphp.com/en/latest/index.html" class="list-group-item">
					Documentation
				</a>
				<a href="http://forum.phalconphp.com/" class="list-group-item"> 
					Forum
				</a>
				<a href="http://blog.phalconphp.com/" class="list-group-item">
					Blog
				</a>
			</div>
		</div> 


		<div class="col-md-9 pull-right">
			<div class="spacer30"></div>

			<h3 class="page-header">You're flying with PhalconPHP</h3>

			<strong>Getting started</strong>
			
			<p>
				The page you are looking at is being generated dynamically by Phalcon.

				If you would like to edit this page you'll find it located at:

				<div class="well">
					<code>app/views/hello/index.volt</code>
				</div>

				Main layout located at:

				<div class="well">
					<code>app/views/index.volt</code>
				</div>

				The corresponding controller for this page was found at:

				<div class="well">
					<code>app/controllers/HelloController.php</code>
				</div>

				Enjoy ;)
			</p>

			<hr>

			<div class="text-right">
				Powered by <a href="https://github.com/GesJeremie/Phalcon-starterkit" target="_blank">StarterKit</a>
			</div>

		</div>

	</div>

</div>


