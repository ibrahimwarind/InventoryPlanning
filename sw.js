 self.addEventListener("install",e=>{
  
  e.waitUntil(
    caches.open(appVersion).then(cache =>{

    	 return cache.addAll(['./','./resources/views/index.blade.php','https://b2bpremier.com/assets/logo192.png']);

    })
  	);   

  });

 self.addEventListener("fetch",e=> {
   
   console.log(`Intercepting Fetch Request for : ${e.request.url}`);


 });