const fetch = require('node-fetch');
const FormData = require('form-data');
const readlineSync = require('readline-sync');
var randomize = require('randomatic')
var md5 = require('md5');

const functionNama = () => new Promise((resolve, reject) => {
       fetch('https://wirkel.com/data.php?qty=1', {
        method: 'GET'
       })
       .then(res => res.json())
      .then(result => {
          resolve(result);
      })
      .catch(err => reject(err))
  });

const functionRegist = (fullName, nomor, email, reff, android) => new Promise((resolve, reject) => {
    const form = new FormData();
    form.append('password', 'Berak321#');
    form.append('nama', fullName);
    form.append('hp', nomor);
    form.append('id_android', android)
    form.append('kode_referral', reff)
    form.append('email', email)
    form.append('f328k47', 'com.cellulermania')
    form.append('r', '37362')
    form.append('api', '3')

       fetch('https://api.bukaolshop.com/olshop/doreg.php', {
        method: 'POST', 
        body: form,
        headers: {
            'Host': 'api.bukaolshop.com',
            'Connection': 'Keep-Alive',
            'Accept-Encoding': 'gzip',
            'User-Agent': 'okhttp/4.1.0'
           }
       })
       .then(res => res.json())
      .then(result => {
          resolve(result);
      })
      .catch(err => reject(err))
  });

(async () => {
    const reff = readlineSync.question('[?] Reff: ')
    const jumlah = readlineSync.question('[?] Jumlah reff: ')
    console.log("")
    for (var i = 0; i < jumlah; i++){
    try {
        const getNama = await functionNama()
        const fullName = `${getNama.result[0].firstname} ${getNama.result[0].lastname}`
        const nomor = getNama.result[0].phone
        const rand = randomize('0', 5)
        const android = md5(rand)
        const email = `${getNama.result[0].firstname}${rand}@gmail.com`
        const regist = await functionRegist(fullName, nomor, email, reff, android)
        if(regist){
            console.log(`[${i+1}] SUKSES !`)
        } else {
            console.log(`[${i+1}] GAGAL !\n`)
        }
    } catch (e) {
        console.log(e)
    }
}
})()