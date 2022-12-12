<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
	        		<h1 class="page-header">CART</h1>
	        		<div class="box box-solid">
	        			<div class="box-body">
		        		<table class="table table-bordered">
		        			<thead>
		        				<th></th>
		        				<th>Photo</th>
		        				<th>Name</th>
		        				<th>Price</th>
		        				<th width="30%">Quantity</th>
		        				<th>Subtotal</th>
		        			</thead>
		        			<tbody id="tbody">
		        			</tbody>
		        		</table>
	        			</div>
<a href="https://www.bkash.com/"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRPFBCaii4cnY0KUFDvhUmaLsehPoq2efMKAwiNt4btOF3ZTaASJbbcIOcAVQ8hIsy5kw&usqp=CAU"></a>
	        		</div>
	        		<?php
	        			if(isset($_SESSION['user'])){
	        				echo "
	        					<div id='bKash'></div>
	        				";
	        			}
	        			else{
	        				echo "
	        					<h4>You need to <a href='login.php?price=<?php eco_Total?>Login</a> to checkout.</h4>
	        				";
	        			}
	        		?>
	        	</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  	<?php $pdo->close(); ?>
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
var total = 0;
$(function(){
	$(document).on('click', '.cart_delete', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: 'cart_delete.php',
			data: {id:id},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	$(document).on('click', '.minus', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		if(qty>1){
			qty--;
		}
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty,
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	$(document).on('click', '.add', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		qty++;
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty,
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	getDetails();
	getTotal();

});

function getDetails(){
	$.ajax({
		type: 'POST',
		url: 'cart_details.php',
		dataType: 'json',
		success: function(response){
			$('#tbody').html(response);
			getCart();
		}
	});
}

function getTotal(){
	$.ajax({
		type: 'POST',
		url: 'cart_total.php',
		dataType: 'json',
		success:function(response){
			total = response;
		}
	});
}
</script>
<!-- bKash -->
<script>
bkash.render({
    env: 'box', // change for production if app is live,

	client: {
         box:    'https://github.com/bKash-developer/bKash-for-woocommerce.git',
    sandbox:    'ALiCzsYBhmudHouSJjGJznskh6U8xjIHiw%3A1670244412971&ei=POiNY7v5OoPqz7sPwsm4kA8&ved=0ahUKEwi7h7GnweL7AhUD9XMBHcIkDvIQ4dUDCA8&uact=5&oq=bkash&gs_lcp=Cgxnd3Mtd2l6LXNlcnAQAzIKCAAQRxDWBBCwAzIKCAAQRxDWBBCwAzIKCAAQRxDWBBCwAzIKCAAQRxDWBBCwAzIKCAAQRxDWBBCwAzIKCAAQRxDWBBCwAzIKCAAQRxDWBBCwAzIKCAAQRxDWBBCwAzIHCAAQsAMQQzIHCAAQsAMQQzIHCAAQsAMQQzIHCAAQsAMQQzISCC4QxwEQ0QMQyAMQsAMQQxgBMhIILhDHARDRAxDIAxCwAxBDGAEyEgguEMcBENEDEMgDELADEEMYATIMCC4QyAMQsAMQQxgBMhIILhDHARDRAxDIAxCwAxBDGAFKBAhBGABKBAhGGAFQAFgAYIUSaAJwAXgAgAEAiAEAkgEAmAEAyAERwAEB2gEGCAEQARgI&sclient=gws-wiz-serp',

    },

    commit: true, // Show a 'Pay Now' button

    style: {
    	color: 'blue',
    	size: 'small'
    },

    payment: function(data, actions) {
        return actions.payment.create({
            payment: {
                transactions: [
                    {
                    	//total purchase
                        amount: { 
                        	total: total, 
                        	currency: 'Taka' 
                        }
                    }
                ]
            }
        });
    },

    onAuthorize: function(data, actions) {
        return actions.payment.execute().then(function(payment) {
			window.location = 'sales.php?pay='+payment.id;
        });
    },

}, '#bKash');
</script>
</body>
</html>