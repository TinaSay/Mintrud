/**
 * Created by user on 21.06.2017.
 */
function changeLang(lang) {
    if (lang === 'en') {
        window.location.href = 'http://www.rosmintrud.ru/eng/';
    }
}
_govWidget = {
    cssOrigin: '//gosbar.gosuslugi.ru',
    catalogOrigin: '//gosbar.gosuslugi.ru',
    disableSearch: true,
    language: {
        visible: ['ru', 'en'],
        onChange: changeLang
    }
};