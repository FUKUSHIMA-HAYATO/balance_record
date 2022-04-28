    <footer>
      <?php if(strpos($_SERVER['REQUEST_URI'],'balance_record/home') !== false): ?>
        <link rel="stylesheet" href="/balance_record/library/air_datepicker/datepicker.min.css">
        <script src="/balance_record/library/air_datepicker/datepicker.min.js"></script>
        <script src="/balance_record/library/air_datepicker/datepicker.jp.js"></script>
        <script src="/balance_record/js/modal_window.js?<?php echo date('Ymd-His'); ?>"></script>
        <script src="/balance_record/js/hamburger.js?<?php echo date('Ymd-His'); ?>"></script>
        <script src="/balance_record/js/tab_function.js?<?php echo date('Ymd-His'); ?>"></script>
        <script src="/balance_record/js/ajax.js?<?php echo date('Ymd-His'); ?>"></script>
        <script src="/balance_record/js/results_of_processing.js?<?php echo date('Ymd-His'); ?>"></script>
        <script src="/balance_record/library/chart.js/chart.min.js?"></script>
      <?php endif; ?>
    </footer>

  </body>
</html>