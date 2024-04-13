
<!DOCTYPE html>
<html lang="en">
<head>
  <title>code.jetlifecdn.com</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript">
<meta name="robots" content="noindex,nofollow">
	window.addEventListener("resize", function() {
		"use strict"; window.location.reload(); 
	});


	document.addEventListener("DOMContentLoaded", function(){

		// make it as accordion for smaller screens
		if (window.innerWidth > 992) {

			document.querySelectorAll('.navbar .nav-item').forEach(function(everyitem){
				
				everyitem.addEventListener('mouseover', function(e){

					let el_link = this.querySelector('a[data-bs-toggle]');

					if(el_link != null){
						let nextEl = el_link.nextElementSibling;
						el_link.classList.add('show');
				 		nextEl.classList.add('show');
					}
					
				});
				everyitem.addEventListener('mouseleave', function(e){
				 	let el_link = this.querySelector('a[data-bs-toggle]');
					
					if(el_link != null){
						let nextEl = el_link.nextElementSibling;
						el_link.classList.remove('show');
				 		nextEl.classList.remove('show');
					}
					

				})
			});

		}
		// end if innerWidth
	}); 
	// DOMContentLoaded  end

</script>
</head>
<body>
    <?php include './z-dynamics/navbar.html';?>

<div class="jumbotron jumbotron-fluid">
  <div class="container-fluid">
    <h1>code.jetlifecdn.com</h1>      
    <p>Home for all things related to coding, automated Ubuntu scripts, and more!</p>
  </div>
</div>
<div class="container">
    <div class="row">
        <div class="col">
    <h2> Page H2</h2>
    <div class="card">
      <div class="card-header">Header</div>
      <div class="card-body">Content</div> 
      <div class="card-footer">Footer</div>
    </div>
    </div>
    <div class="col">
    <h2> Page H2</h2>
    <div class="card">
      <div class="card-header">Header</div>
      <div class="card-body">Content</div> 
      <div class="card-footer">Footer</div>
    </div>
    </div>
    <div class="col">
    <h2> Page H2</h2>
    <div class="card">
      <div class="card-header">Header</div>
      <div class="card-body">Content</div> 
      <div class="card-footer">Footer</div>
    </div>
        </div>
  </div>
</div>
</body>
</html>
