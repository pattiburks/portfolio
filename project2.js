$(document).ready(function() {
	// initialize the itemCount variable and a delete button variable
	var itemCount = 0;
	var delButton = " <span class='del'>Remove</span>";
	// add a click function to the add class to add to shopping cart
	$('.add').click(function() {
		// get the text from the figcaption to use as the item for the shopping cart
			var item=$(this).siblings('figcaption').text();
			// if this is the first item, remove the div from around the paragraph for the empty cart and empty the paragraph
			if (itemCount==0) {
				$('#empty').unwrap();
				$('#empty').empty();
			}
			// append the item and remove button to the cart.
			$('#cart').append("<li>"+item+delButton+"</li>");
			//increment the item count
			itemCount++;
	}); // end add click
	// add a click function to the remove buttons in the shopping cart using delegate since the buttons do not exist until added
	$('#cart').on('click','.del',function() {
		// delete the li from the shopping cart
		$(this).parent('li').remove();
		// decrement the item count
		itemCount--;
		// if the item count is at 0, then add the empty shopping cart message and wrap it in a div
		if (itemCount == 0) {
			$('#empty').text('Your shopping cart is empty');
			$('#empty').wrap('<div class="wrapper"></div>');
		}
	}); // end delegate
	
	// when a star is clicked in the ratings
	$('.rating img').click(function() {
		// save the star selector in a variable
		thisStar = $(this);
		// change the image src to the star on gif file
		thisStar.attr('src','staron.gif');
		// change the image src of all previous siblings (stars) to the star on image and the following ones to star off.
		thisStar.prevAll().attr('src','staron.gif');
		thisStar.nextAll().attr('src','staroff.gif');
	}); // end star click
}); // end ready
