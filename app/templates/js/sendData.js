export default async function sendData(endpoint, data = {})
{
   let requestOptions = {
      method: "POST",
      Headers: {
         "Content-type": "application/json"
      },
      body: JSON.stringify(data)
   };

   try
   {
      const RESPONSE = await fetch(endpoint, requestOptions);
      return await RESPONSE.json();
   }
   catch(e)
   {
      console.log(e);
   }
}