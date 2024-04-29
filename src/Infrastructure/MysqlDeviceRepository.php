<?php

namespace Dm1tru\Barcoder\Infrastructure;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\Entity\Device;
use Dm1tru\Barcoder\Domain\Repository\BarcodeRepositoryInterface;
use Dm1tru\Barcoder\Domain\Repository\DeviceRepositoryInterface;
use Dm1tru\Barcoder\Domain\ValueObject\Code;
use Dm1tru\Barcoder\Domain\ValueObject\Count;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;
use Dm1tru\Barcoder\Domain\ValueObject\Ip;
use Dm1tru\Barcoder\Domain\ValueObject\Name;
use Dm1tru\Barcoder\Domain\ValueObject\Order;
use PDO;
use PDOStatement;

class MysqlDeviceRepository implements DeviceRepositoryInterface
{
    private PDO $pdo;
    private PDOStatement $_qInsert;
    private PDOStatement $_qGetAll;

    public function __construct()
    {
        $this->connect();

        $this->_qInsert = $this->pdo->prepare(
            "INSERT INTO devices 
            SET name= :name, host = :host, order = :order"
        );

        $this->_qGetAll = $this->pdo->prepare(
            "SELECT id, name, host, `order`
            FROM devices
            ORDER BY `order`, id"
        );
    }

    private function connect(): void
    {
        $conf = parse_ini_file('config.ini', true);
        var_dump($conf);
        $dsn = "mysql:host=$conf[MYSQL_HOST];dbname=$conf[MYSQL_DATABASE]";
        $this->pdo = new \PDO($dsn, $conf['MYSQL_USER'], $conf['MYSQL_PASSWORD']);
    }

    public function add(Device $device): Id
    {
        $this->_qInsert->execute([
            'name' => $device->getName()->getName(),
            'count' => $device->getCount()->getCount(),
            'host' => $device->getHost()->getIp(),
            'order' => $device->getOrder()->getOrder()
        ]);

        return new Id($this->pdo->lastInsertId());
    }

    public function getAll(): array
    {
        $devices = [];

        $this->_qGetAll->execute();
        while ($ret = $this->_qGetAll->fetch(PDO::FETCH_ASSOC)) {
            $devices[] = new Device(
                new Id($ret['id']),
                new Name($ret['name']),
                new Ip($ret['host']),
                new Order($ret['order'])
            );
        }

        return $devices;
    }

    public function getById(Id $id): Device
    {
        return new Device();
    }
}
