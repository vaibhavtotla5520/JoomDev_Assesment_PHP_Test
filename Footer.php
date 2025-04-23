<div class="footer">
    <div class="row">
        <div class="col-lg-12">
            &copy;
        </div>
    </div>
</div>
<!-- /. WRAPPER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.min.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function getQueryParams() {
        const params = new URLSearchParams(window.location.search);
        const result = {};
        for (const [key, value] of params.entries()) {
            result[key] = value;
        }
        return result;
    }
    
    function clearQueryParams() {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    const queryParams = getQueryParams();
    if (Object.keys(queryParams).length > 0) {
        let alertMessage = "Query Parameters:\n";
        for (const [key, value] of Object.entries(queryParams)) {
            alertMessage += `${key}: ${value}\n`;
        }
        alert(alertMessage);
        clearQueryParams();
    }
});
</script>