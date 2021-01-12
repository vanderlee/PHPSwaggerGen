<?php
declare(strict_types=1);

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger License object, containing the licensing information that
 * applies to the documented API.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class License extends AbstractObject
{

    private const LICENSE_APACHE_2 = 'http://www.apache.org/licenses/LICENSE-2.0.html';
    private const LICENSE_ARTISTIC_2 = 'http://opensource.org/licenses/artistic-license-2.0';
    private const LICENSE_EPL_1 = 'http://www.eclipse.org/legal/epl-v10.html';
    private const LICENSE_GPL_3 = 'http://www.gnu.org/licenses/gpl-3.0.html';


    // @todo make this a separate resource file? (licenseUrls.json)
    private static $licenses = [
        'artistic-1.0' => 'http://opensource.org/licenses/artistic-license-1.0',
        'artistic-1'   => 'http://opensource.org/licenses/artistic-license-1.0',
        'artistic-2.0' => self::LICENSE_ARTISTIC_2,
        'artistic-2'   => self::LICENSE_ARTISTIC_2,
        'artistic'     => self::LICENSE_ARTISTIC_2,
        'bsd-new'      => 'https://opensource.org/licenses/BSD-3-Clause',
        'bsd-3'        => 'https://opensource.org/licenses/BSD-3-Clause',
        'bsd-2'        => 'https://opensource.org/licenses/BSD-2-Clause',
        'bsd'          => 'https://opensource.org/licenses/BSD-2-Clause',
        'epl-1.0'      => self::LICENSE_EPL_1,
        'epl-1'        => self::LICENSE_EPL_1,
        'epl'          => self::LICENSE_EPL_1,
        'apache-2.0'   => self::LICENSE_APACHE_2,
        'apache-2'     => self::LICENSE_APACHE_2,
        'apache'       => self::LICENSE_APACHE_2,
        'gpl-1.0'      => 'https://www.gnu.org/licenses/gpl-1.0.html',
        'gpl-1'        => 'https://www.gnu.org/licenses/gpl-1.0.html',
        'gpl-2.0'      => 'https://www.gnu.org/licenses/gpl-2.0.html',
        'gpl-2'        => 'https://www.gnu.org/licenses/gpl-2.0.html',
        'gpl-3.0'      => self::LICENSE_GPL_3,
        'gpl-3'        => self::LICENSE_GPL_3,
        'gpl'          => self::LICENSE_GPL_3,
        'lgpl-2.0'     => 'http://www.gnu.org/licenses/lgpl-2.0.html',
        'lgpl-2.1'     => 'http://www.gnu.org/licenses/lgpl-2.1.html',
        'lgpl-2'       => 'http://www.gnu.org/licenses/lgpl-2.1.html',
        'lgpl-3.0'     => 'http://www.gnu.org/licenses/lgpl-3.0.html',
        'lgpl-3'       => 'http://www.gnu.org/licenses/lgpl-3.0.html',
        'lgpl'         => 'http://www.gnu.org/licenses/lgpl-3.0.html',
        'mit'          => 'http://opensource.org/licenses/MIT',
        'mpl-1.1'      => 'https://www.mozilla.org/en-US/MPL/1.1/',
        'mpl-1'        => 'https://www.mozilla.org/en-US/MPL/1.1/',
        'mpl-2.0'      => 'https://www.mozilla.org/en-US/MPL/',
        'mpl-2'        => 'https://www.mozilla.org/en-US/MPL/',
        'mpl'          => 'https://www.mozilla.org/en-US/MPL/',
        'mspl'         => 'https://msdn.microsoft.com/en-us/library/ff648068.aspx',
    ];
    private $name;
    private $url;

    public function __construct(AbstractObject $parent, $name, $url = null)
    {
        parent::__construct($parent);

        $this->name = empty($name) ? null : $name;

        if (!empty($url)) {
            $this->url = $url;
        } elseif (!empty(self::$licenses[strtolower($name)])) {
            $this->url = self::$licenses[strtolower($name)];
        }
    }

    /**
     * @param string $command
     * @param string $data
     *
     * @return AbstractObject|boolean
     */
    public function handleCommand($command, $data = null)
    {
        switch (strtolower($command)) {
            case 'name':
                $this->name = $data;
                if (empty($this->url) && !empty(self::$licenses[strtolower($data)])) {
                    $this->url = self::$licenses[strtolower($data)];
                }

                return $this;

            case 'url':
                $this->url = $data;

                return $this;

            default:
                break;
        }

        return parent::handleCommand($command, $data);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return self::arrayFilterNull(array_merge([
            'name' => $this->name,
            'url'  => $this->url,
        ], parent::toArray()));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . " {$this->name}, {$this->url}";
    }

}
