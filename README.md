# Zaraki

yet another one time session manager

## features

```
$session = \Zaolik\SessionManager(new \Zaolik\SimpleSession());
// write 'key' => 'value'  to session variables for onetime
$session->set('key', 'value');

// $value is 'value'. 'key' => 'value' is deleted from session variables.
$value = $session->get('key');

// write 'key' => 'value'  to session variables for permanent
$session->setPermanent('key', 'value');

// $value is 'value'. 'key' => 'value' is not deleted from session variables.
$value = $session->getPermanent('key');

$session->flush();
```


## License

This library is available under the MIT license. See the LICENSE file for more info.

