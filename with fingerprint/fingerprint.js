import FingerprintJS from '@/node-modules/fingerprintjs/fingerprintjs-pro';

// Wait a couple of seconds before a result appears

// Initialize an agent at application startup.
const fpPromise = FingerprintJS.load({
  // Get a public API key at https://dashboard.fingerprintjs.com
  apiKey: 'SwiW486DS1z0dXiwvRaN',
  // region: 'ap',
  // endpoint: 'https://fp.your.com',
});

(async () => {
  // Get the visitor identifier when you need it.
  const fp = await fpPromise;
  const result = await fp
    .get
    /* { linkedId: 'your-linked-id', tag: { yourTag: 123456 } } */
    ();

  const visitorId = result.visitorId;
  console.log('Your visitorId:', visitorId);
})();
