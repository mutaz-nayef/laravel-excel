<div id="primary-side" class="side  flow">
    <div class="logo flex-col flow">
        <img src="{{asset('images/Logo.png')}}" alt="Site Logo">
        <span>Udemy Inter.school</span>
    </div>

    <ul class="links flow-sm" role="list">
        <li><a href="#" data-url="{{ route('dashboard') }}"> <span><img src="{{asset('images/home.svg')}}"
                        alt=""></span> Dashboard</a></li>
        <li><a href="#"> <span><img src="{{asset('images/home.svg')}}" alt=""></span> Teachers</a></li>
        <li class="list-menu">
            <a href="#" class="toggle-sub-menu"> <span><img src="{{asset('images/home.svg')}}" alt=""></span>
                Students <i class="fas fa-chevron-down"></i>
            </a>

            <ul class="sub-menu flow-sm" role="list">
                <li><a href="#" data-url="{{ route('students.index') }}">All Students</a></li>
                <li><a href="#" data-url="{{ route('students.create') }}">Add Students</a></li>
                <li><a href="#" data-url="{{ route('students.imported') }}">Imported Students</a>
                </li>
            </ul>
        </li>

        <li><a href="#"> <span><img src="{{asset('images/billing.svg')}}" alt=""></span>Billing</a></li>
        <li><a href="#"><span><img src="{{asset('images/setting.svg')}}" alt=""></span>Settings & Profile</a></li>
    </ul>
</div>