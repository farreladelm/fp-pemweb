window.addEventListener('scroll', scrollHeader)

const login_btn = document.getElementById('login');

login_btn.addEventListener('click', function(){
    const login_form = document.querySelector('.center');
    login_form.classList.toggle('show');
})