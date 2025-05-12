document.addEventListener('DOMContentLoaded', () => {
    const authBlock = document.querySelector('.header__auth');
    const username = getCookie('user_login');

    if (username && authBlock) {
        authBlock.innerHTML = `
            <a href="profile.html" class="btn btn-profile">Профиль</a>
            <a href="#" class="btn btn-logout">Выйти</a>
        `;

        document.querySelector('.btn-logout').addEventListener('click', () => {
            document.cookie = "user_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "user_login=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            location.reload();
        });
    }
});

function getCookie(name) {
    const matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([$?*|{}\\[\]])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}