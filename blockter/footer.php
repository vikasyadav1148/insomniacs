<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the #content div and all content after
 * @package blockter
 */
/*page option*/
$pid = get_queried_object_id();
$cr_display = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($pid, 'p_copyright') : 'default';
/*customizer*/
$copyright = get_theme_mod('c_copyright', '&copy; ' . date( 'Y' ) . ' Blockter. All Rights Reserved. Designed by <a href="http://themeforest.net/user/haintheme">Haintheme</a>.');
$footer_bgc = get_theme_mod( 'ft_bg_color', '#03111b' );
?>
	<footer class="theme-footer flw sticky-stopper" style="background-color:<?php echo esc_attr($footer_bgc);  ?>;">



		<?php blockter_footer_edit_location('footer');/*footer edit location*/ ?>
		<?php blockter_footer_display(); ?>

		

		<?php if($cr_display != 'disable'):/*page disable copyright*/ ?>
			<?php if(!empty($copyright)): ?>
			<div class="coppy-right flw">
				<div class="container flex-ft-item">
					<span><?php echo wp_kses($copyright, array('a'=>array('href'=>array(), 'target'=>array()))); ?></span>
				<a class="scroll-to-top">
						<span><?php echo esc_html__('Back to top', 'blockter');?></span>
						<span class="scroll-to-top ion-ios-arrow-thin-up"></span>
					</a>
				</div>
			</div>
		<?php endif; ?>
		<?php endif;/*end page disable copyright*/ ?>
	</footer>

	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/custom.css?v=<?php echo filemtime(get_template_directory() . '/css/custom.css'); ?>">

    <!-- <script>
    $(document).on("ready", function () {
        if ($('body').hasClass('openform')) {  // Correct usage of hasClass
            console.log("Added");
        }
    });
</script> -->



	<?php wp_footer(); ?>

	<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const filterContainer = document.querySelector('.category-filter');
    const buttons = filterContainer.querySelectorAll('button');
    const isMobile = window.matchMedia("(max-width: 768px)").matches;

    if (isMobile) {
        let dropdown = document.createElement('select');
        dropdown.className = 'category-dropdown';

        buttons.forEach(button => {
            let option = document.createElement('option');
            option.value = button.getAttribute('data-term');
            option.textContent = button.textContent;
            dropdown.appendChild(option);
            button.style.display = 'none'; // Hide the original buttons on mobile
        });

        filterContainer.appendChild(dropdown);

        dropdown.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedTerm = selectedOption.value;
            document.querySelector(`button[data-term="${selectedTerm}"]`).click();
        });
    }
});
</script> -->

<!-- <script>
	document.getElementById('categoryDropdown').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const filter = selectedOption.value;
    const page = selectedOption.getAttribute('data-page');
    const media = selectedOption.getAttribute('data-media');

    // Trigger the filtering functionality here
    console.log(`Filtering ${media} with term: ${filter} on page: ${page}`);

    // Implement the actual filtering logic, possibly similar to the button click handling logic
});

</script> -->

<script>
// document.getElementById('categoryDropdown').addEventListener('change', function() {
//     const selectedOption = this.options[this.selectedIndex];
//     console.log(categoryDropdown);
//     const filter = selectedOption.getAttribute('data-term');
//     const page = selectedOption.getAttribute('data-page');
//     const media = selectedOption.getAttribute('data-media');

//     // Simulate a button click for the selected category
//     document.querySelector(`button[data-term="${filter}"]`).click();
    
//     // console.log(`Filtering ${media} with term: ${filter} on page: ${page}`);
// });





// document.addEventListener("DOMContentLoaded", function() {
//     // Select the login form
//     var loginForm = document.querySelector(".login-form");

//     // Select the login-submit block
//     // var loginSubmitBlock = document.querySelector(".login-submit");

//     // Create a new div element to hold the HTML (assuming this is what the shortcode outputs)
//     var shortcodeHTML = document.createElement("div");
//     shortcodeHTML.className = "custom-shortcode"; // Add a class for styling if needed

//     // Set the innerHTML to what you want to appear before the login submit button
//     shortcodeHTML.innerHTML = '<div>[miniorange_social_login shape="longbuttonwithtext" theme="default" space="8" width="180" height="35" color="000000"]</div>';

//     // Insert the new HTML before the login-submit block
//     loginForm.appendChild(shortcodeHTML);
// });


// Select all dropdowns on the page
document.querySelectorAll('#categoryDropdown').forEach(function(dropdown) {
    dropdown.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const filter = selectedOption.getAttribute('data-term');
        const page = selectedOption.getAttribute('data-page');
        const media = selectedOption.getAttribute('data-media');
        
        // Ensure the button click targets the correct block by finding the closest related buttons
        const parentBlock = this.closest('.category-filter');
        const targetButton = parentBlock.querySelector(`button[data-term="${filter}"]`);
        
        if (targetButton) {
            targetButton.click();
        } else {
            console.error('No matching button found for the selected option.');
        }
    });
});




</script>
</body>
</html>



