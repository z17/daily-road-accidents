document.addEventListener('click', function(event) {
  if (event.target.dataset.sectionTarget !== undefined) {
    const targetIndex = event.target.dataset.sectionTarget;
    const item = document.querySelector(`[data-section="${targetIndex}"]`);
    if(item) {
      item.scrollIntoView({ behavior: "smooth"})
    }
  }

  if (event.target.dataset.content === 'spoiler_button') {
    event.target.classList.toggle('active');
    const elem = event.target.closest('[data-content="spoiler_wrapper"]').querySelector('[data-content="spoiler_content"]');
    elem.classList.toggle('active');
  }
});


const statisticsElem = document.querySelector('#statistics');
const statisticsELemTitlesMap = {
  accidents: 'Всего ДТП',
  deaths: 'Погибли',
  child_deaths: 'Погибли детей',
  injured: 'Ранены',
  child_injured: 'Ранены детей',
  date: 'ДТП в России за',
}

fetch('http://stats.blweb.ru/stats/').then((response) => {
  return response.json();
}).then(v=>{
  const {accidents, deaths, child_deaths, injured, child_injured, date} = v;
  let html = '';
  if (date) {
    html+= `<div class="statistics-title">${statisticsELemTitlesMap.date}:</td> <td>${new Date(date * 1000).toLocaleDateString('ru')}</div>`
  }
  html+='<table class="statistics-table">'
  if(accidents) {
    html+= `<tr><td>${statisticsELemTitlesMap.accidents}:</td> <td><b>${accidents}</b></td></tr>`
  }
  if(deaths) {
    html+= `<tr><td>${statisticsELemTitlesMap.deaths}:</td> <td><b>${deaths}</b></td></tr>`
  }
  if(injured) {
    html+= `<tr><td>${statisticsELemTitlesMap.injured}:</td> <td><b>${injured}</b></td></tr>`
  }
  if(child_deaths) {
    html+= `<tr><td>${statisticsELemTitlesMap.child_deaths}:</td> <td><b>${child_deaths}</b></td></tr>`
  }
  if(child_injured) {
    html+= `<tr><td>${statisticsELemTitlesMap.child_injured}:</td> <td><b>${child_injured}</b></td></tr>`
  }

  html += '</table>'
  statisticsElem.innerHTML = html;
}).catch(()=>{
  statisticsElem.remove()
})
