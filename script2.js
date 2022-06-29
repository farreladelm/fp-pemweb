window.addEventListener('scroll', scrollHeader)

const login_btn = document.getElementById('login');
const exit_btn = document.getElementById('exit');

login_btn.addEventListener('click', function(){
    const login_form = document.querySelector('.center');
    login_form.classList.toggle('show');
})

exit_btn.addEventListener('click', function(){
    const login_form = document.querySelector('.center');
    login_form.classList.remove('show');
})

