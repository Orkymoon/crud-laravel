<nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgba(0, 0, 0, 0.2)">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img class="rounded" src="/img/crud.png" alt="crud"
                style="height: 4rem" /></a>
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="{{ route('customer.create') }}">Create</a>
                <a class="nav-link active" aria-current="page" href="{{ route('customer.index') }}">Read</a>
                <a class="nav-link active" aria-current="page" href="">Update</a>
                <a class="nav-link active show-input-delete-box" aria-current="page" href="">Delete</a>
            </div>
        </div>
    </div>
</nav>
