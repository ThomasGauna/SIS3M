(function(){
const menu = document.getElementById('menu');
if (!menu) return;

const KEY = 'mini_erp_menu_open';
const state = JSON.parse(localStorage.getItem(KEY) || '{}');
const save = () => localStorage.setItem(KEY, JSON.stringify(state));

function setOpen(group, open){
state[group] = !!open; save();
const sub   = menu.querySelector(`.submenu[data-for="${group}"]`);
const caret = menu.querySelector(`.menu-group[data-group="${group}"] .caret`);
if (sub)   sub.classList.toggle('open', !!open);
if (caret) caret.style.transform = open ? 'rotate(0deg)' : 'rotate(-90deg)';
}

menu.querySelectorAll('.menu-group').forEach(btn=>{
const g = btn.dataset.group;
setOpen(g, state[g] !== false);
btn.addEventListener('click', ()=> setOpen(g, !(state[g] !== false)));
});

document.getElementById('collapseAll')?.addEventListener('click', ()=>{
menu.querySelectorAll('.menu-group').forEach(btn=> setOpen(btn.dataset.group, false));
});

const path = (location.pathname.split('/').pop() || 'index.html').split('?')[0].split('#')[0];
menu.querySelectorAll('.submenu a').forEach(a=>{
const href = a.getAttribute('href').split('?')[0].split('#')[0];
if (href.endsWith(path)) {
    a.classList.add('active');
    const group = a.closest('.submenu')?.getAttribute('data-for');
    if (group) setOpen(group, true);
}
});

window._sidebarSetOpen = setOpen;
})();