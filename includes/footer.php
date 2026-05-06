    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        let searchTimer;
        function autoSearch() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                const searchInput = document.activeElement;
                if (searchInput && searchInput.form) {
                    searchInput.form.submit();
                }
            }, 500); // Wait for 500ms after last keystroke
        }

        function disableCreateBtn(form) {
            const btn = document.getElementById('createModuleBtn');
            if(btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
            }
            return true;
        }

        // Maintain focus on search input after reload
        document.addEventListener('DOMContentLoaded', function() {
            const searchParams = new URLSearchParams(window.location.search);
            if (searchParams.has('search')) {
                const searchInput = document.getElementById('moduleSearch') || document.getElementById('studentSearch');
                if (searchInput) {
                    searchInput.focus();
                    // Move cursor to end of text
                    const val = searchInput.value;
                    searchInput.value = '';
                    searchInput.value = val;
                }
            }
        });
    </script>
  </body>
</html>