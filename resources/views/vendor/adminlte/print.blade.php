<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Print</title>

		<style type="text/css">
			@media print {
				@page {
					size: landscape;
				}
				form {
					display:none;
				}
				.card-body {
					font-size:5px;
				}
				table {
					border-collapse: collapse;
					border: 1px solid grey;
				}
				table td {
					border-collapse: collapse;
					border: 1px solid grey;
				}
			}
		</style>
	</head>
	<body>
		@yield('content')
	</body>
</html>
