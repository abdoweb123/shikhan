<footer class="footer" style="background-color: #d9cbab;padding: 25px 0px 25px 0px;">
    <div class="container">
        <div class="container-fluid">
            <nav class="text-center">
                <ul style="display: inline-flex;">
                    @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
                        <li>
                            <img src="{{ asset('assets/img/icons/dr.png') }}" alt="د/عبد الله باهمام" title="د/عبد الله باهمام" class='img-fluid'>
                            <span style="font-size: 12px;font-weight: bold;"><a class="nav-link" href="https://www.ba-hammam.com/ar">د/عبد الله باهمام</a></span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/img/icons/fada-qnwat.png') }}" alt="فضاء ميديا للبرمجيات" title="فضاء ميديا للبرمجيات" class='img-fluid'>
                            <span style="font-size: 12px;font-weight: bold;"><a class="nav-link" href="https://www.spacechanels.com/">فضاء القنوات</a></span>
                        </li>
                    @else
                        <li>
                            <img src="{{ asset('assets/img/icons/dr.png') }}" alt="ba-hammam" title="ba-hammam" class='img-fluid'>
                            <span style="font-size: 12px;font-weight: bold;"><a class="nav-link" href="https://www.ba-hammam.com/ar">ba-hammam</a></span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/img/icons/fada-qnwat.png') }}" alt="spacechanels" title="spacechanels" class='img-fluid'>
                            <span style="font-size: 12px;font-weight: bold;"><a class="nav-link" href="https://www.spacechanels.com/">spacechanels</a></span>
                        </li>
                    @endif
                </ul>
                @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
                    <div><p>جميع الحقوق محفوظة &copy; courses.al-feqh.com -all </p></div>
                @else
                    <div><p>copyrightes &copy; courses.al-feqh.com -all rightes reserved </p></div>
                @endif
            </nav>
        </div>
    </div>
</div>
</footer>
