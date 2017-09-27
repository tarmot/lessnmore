</div><!--#wrap-->
<?php if (user_is_logged_in()): ?>
	<div id="footer-update-area" data-version="<?php echo BCURLS_VERSION ?>">
		<noscript>
			Enable JavaScript for automatic update checking or go to the
			<a href="https://lessnmore.net/">Lessn More home page</a> to check for updates.
			You are on Lessn More <?php echo BCURLS_VERSION ?>.
		</noscript>
	</div>
	<script src="js/jquery-1.5.min.js"></script>
	<script src="js/version_check.js"></script>
<?php endif; ?>
</body>
</html>
