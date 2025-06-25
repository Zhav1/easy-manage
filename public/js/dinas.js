document.addEventListener('DOMContentLoaded', function() {
  // First, wait for the datepicker to initialize
  setTimeout(() => {
    // 1. Style the Today button (as you already have working)
    const todayBtn = document.querySelector('.today-btn');
    if (todayBtn) {
      todayBtn.className = 'button today-btn !bg-green-600 hover:!bg-green-700 dark:!bg-green-700 dark:hover:!bg-green-800 text-white font-medium rounded-lg text-sm px-5 py-2 text-center w-1/2 focus:ring-4 focus:ring-green-300';
    }

    // 2. Create a MutationObserver to watch for date selections
    const observer = new MutationObserver((mutations) => {
      // Find all selected dates and style them
      document.querySelectorAll('datepicker-cell.block.flex-1.leading-9.border-0.rounded-lg.cursor-pointer.text-center.font-semibold.text-sm.day.selected.bg-blue-700.!bg-primary-700.text-white.dark:bg-blue-600.dark:!bg-primary-600.focused').forEach(el => {
        el.classList.add('!bg-green-600', '!text-white');
        el.classList.remove('bg-blue-700', '!bg-primary-700');
      });
    });

    // Start observing the datepicker container
    const datepickerEl = document.querySelector('.datepicker');
    if (datepickerEl) {
      observer.observe(datepickerEl, {
        childList: true,
        subtree: true
      });
    }

    // 3. Also style any initially selected date
    document.querySelectorAll('.datepicker-cell.selected').forEach(el => {
      el.classList.add('!bg-green-600', '!text-white');
      el.classList.remove('bg-blue-700', '!bg-primary-700');
    });
  }, 100); // Small delay to ensure datepicker is initialized
});