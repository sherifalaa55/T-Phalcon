<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>You fly on PhalconPHP</title>

	<!--
		- Main Layout
		@ Autoloaded by PhalconPHP 

		- Informations : 
			Views	| http://docs.phalconphp.com/en/latest/reference/views.html
	-->


	<!--
		- Bootstrap
		@ Load Bootstrap Css

		- Notes : 
			It's the Volt syntax to add a stylesheet, we use "false" because it's a remote url

		- Informations : 
			Volt Template | http://docs.phalconphp.com/en/latest/reference/volt.html
			Assets 		  | http://docs.phalconphp.com/en/latest/reference/assets.html
	-->

	{{ stylesheet_link('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css', false) }}

	<style>
		.spacer30 { margin-top : 30px; }
		img.logo { width : 200px; }
	</style>

</head>
<body>

	{{ content() }}

</body>
</html>