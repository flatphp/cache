<?php
use Flatphp\Memstore\Conn;
use Flatphp\Cache\Cache;
use Flatphp\Cache\Backend as CacheBackend;
use Flatphp\Cache\Adapter\Memcache as MemcacheAdapter;

class CacheTest extends PHPUnit_Framework_TestCase
{
    protected $_config = [];

    public function setUp()
    {
        $this->_config = array(
            'memcache' => ['host' => '127.0.0.1', 'port' => 11211]
        );
    }

    public function tearDown()
    {
    }

    public function testBackendFromNew()
    {
        Conn::init($this->_config);
        $memcache_adapter = new MemcacheAdapter(Conn::getMemcache());
        $backend = new CacheBackend($memcache_adapter);
        $this->_testBackend($backend);
    }

    public function testBackendFromStatic()
    {
        Cache::init(array(
            'store' => $this->_config['memcache'] + ['driver' => 'memcache']
        ));
        $backend = Cache::getBackend();
        Cache::flush();
        Cache::set('aa', 3);
        $val = Cache::get('aa', 3);
        $this->assertEquals($val, 3);
        $this->_testBackend($backend);
    }

    protected function _testBackend($backend)
    {
        $backend->flush();
        $val = $backend->get('tt');
        $this->assertEquals($val, null);

        $backend->set('tt', 11);
        $val = $backend->get('tt');
        $this->assertEquals($val, 11);

        $backend->increment('tt');
        $backend->increment('tt');
        $val = $backend->get('tt');
        $this->assertEquals($val, 13);

        $backend->decrement('tt');
        $val = $backend->get('tt');
        $this->assertEquals($val, 12);

        $backend->delete('tt');
        $val = $backend->get('tt');
        $this->assertEquals($val, null);

        $data = $backend->getData('tt2', function(){
            return 'hello';
        });
        $val = $backend->get('tt2');
        $this->assertEquals($data, 'hello');
        $this->assertEquals($val, 'hello');
    }
}
