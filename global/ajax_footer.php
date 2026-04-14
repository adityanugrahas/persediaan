<?php
/**
 * AJAX Footer Helper
 */
if (!$is_ajax): ?>
    <!-- Back to Dashboard Button for standalone mode -->
    <div class="text-center mt-4">
        <a href="../index.php" class="btn btn-primary" style="border-radius:30px; padding:10px 30px;">
            <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <script src="../vendor/jquery/jquery.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>
<?php endif; ?>
