<div class="cargando">
    <div id="loader">
        <h4 class="text-white text-center animated animated-sm bounceInUp">

            <center>
                <x-logo width="300px"/>

                <div class="infinityChrome">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>

                <div class="infinity">
                    <div>
                        <span></span>
                    </div>
                    <div>
                        <span></span>
                    </div>
                    <div>
                        <span></span>
                    </div>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" style="display: none;">
                    <defs>
                        <filter id="goo">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="6" result="blur" />
                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
                            <feBlend in="SourceGraphic" in2="goo" />
                        </filter>
                    </defs>
                </svg>

            </center>

        </h4>
    </div>
</div>

<script>

    $(document).ready(function(){
        $(window).load(function() {
            $(".cargando").fadeOut(2500);
        });
        var progressBar = $("#myProgressBar");
        var progressStatus = $("#progressStatus");
        var progressValue = 0;
        var interval = setInterval(function() {
            progressValue += 5;
            progressBar.css("width", progressValue + "%").attr("aria-valuenow", progressValue);
        }, 50);

    });

</script>
