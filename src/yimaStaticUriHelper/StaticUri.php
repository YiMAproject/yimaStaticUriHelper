<?php
namespace yimaStaticUriHelper;

class StaticUri
{
    const PATH_BASE_PATH  = 'basepath';

    const PATH_SERVER_URL = 'serverurl';

    /**
     * key value of paths name and uri
     *
     * @var array
     */
    protected $pathNames = array();

    /**
     * Is allowed path names to override ?
     *
     * @var bool
     */
    protected $allowOverride = false;

    /**
     * Is initialized ? init()
     *
     * @var bool
     */
    protected $initialized = false;

    /**
     * Last invoked path name
     *
     * @var string
     */
    protected $lastInvokedPath;

    /**
     * Last invoked uri
     *
     * @var string
     */
    protected $lastInvokedUri;

    /**
     * Some default variables that can placed inside uri's
     *
     * @var
     */
    protected $variables = array();

    /**
     * Constructor Setter
     *
     * @param array $options Setter key/value pair include setterMethod-
     *                       option and arguments
     */
    public function __construct($options = array())
    {
        if (!empty($options))
            foreach ($options as $method => $arg) {
                $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $method)));
                if (method_exists($this, $setter)) {
                    $this->{$setter}($arg);
                } else {
                    throw new \InvalidArgumentException(
                        sprintf(
                            'The option "%s" does not have a matching %s setter method',
                            $method, $setter
                        )
                    );
                }
            }
    }

    /**
     * Call as functor
     *
     * usage:
     * ----------------------------------
     * staticUri();
     * staticUri('self'); return self object
     *
     * @return $this|mixed|string
     */
    public function __invoke()
    {
        if (! $this->initialized) {
            $this->init();
        }

        $funcArgs = func_get_args();
        if (empty($funcArgs)) {
            // staticUri()
            return $this->lastInvokedUri;
        }

        $pathName = array_shift($funcArgs);

        if (strtolower($pathName) === 'self') {
            // return self
            return $this;
        }

        if ($this->hasPath($pathName)) {
            $uri = $this->getPath($pathName);
        } else {
            // we don't have pathName, assume that entered text is uri
            $uri = $pathName;
        }

        $assembledUri = call_user_func_array(
            array($this, 'assembleUri'),
            array_merge(array($uri), $funcArgs) // we want uri as first argument
        );

        $assembledUri = rtrim($assembledUri, '/');

        $this->lastInvokedUri  = $assembledUri;
        $this->lastInvokedPath = $pathName;

        return $assembledUri;
    }

    /**
     * When service locator get this class as service,
     * constructor will call and next dependencies injected into
     * like View with setView()
     *
     * We init class on first invoke and prepare to go.
     */
    protected function init()
    {
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

        // default uri is BasePath
        $this->lastInvokedUri = $this->assembleUri(
            $this->getPath(self::PATH_BASE_PATH)
        );
    }

    /**
     * Assemble Uri
     *
     * usage:
     * -------------------
     * assembleUri($uri);
     * assembleUri($uri, array('variable' => 'value'));
     *
     * // default class values not working here
     * assembleUri('var1/var2/var3', $var1, $var2, $var3);
     *
     * @param string $uri Uri or Registered Path
     *
     * @return mixed
     * @throws \Exception
     */
    public function assembleUri($uri)
    {
        $args = func_get_args();
        array_shift($args); // drop out uri from arguments cause we have it

        // get function argument vars
        $vars = array();
        $isKV = false;
        if (!empty($args)) {
            if (is_array($args[0])) {
                // variables posted in form of key => value array in sec. argument
                $vars = $args[0];

                $isKV = true;
            } else {
                // value posted by argument order, exp. (uri, v1, v2, v3, ....)
                $vars = $args;
            }
        }

        $self = $this;
        $vars = ($isKV) ? array_merge($this->getVariables(), $vars)
            : (
            (empty($vars))
                // we use default values
                ? call_user_func(
                function() use ($self, &$isKV) {
                    $isKV = true;

                    return $self->getVariables();
                }
            )
                // we have ordered arguments and can't use default variables here
                : $vars
            );

        // get variables from uri
        $matches = array();
        /**
         * $matches[0] retrun array of full variables matched, exp. $path
         * $matches[1] retrun array of variables name matched, exp. path
         *
         * in haa be dalile vojood e parantes haast
         */
        preg_match_all('/\$(\w[\w\d]*)/', $uri, $matches);

        if (count($matches[0]) === 0) {
            // we don't have any variable in uri
            return $uri;
        }

        if ($isKV) {
            // correct order of variables
            // 'path' => 'ValuablePath' TO 0 => 'ValuablePath'
            foreach ($matches[1] as $i => $v) {
                if (! isset($vars[$v])) {
                    throw new \Exception(
                        sprintf('Value of variable "%s" not found.', $v)
                    );
                }

                $vars[$i] = $vars[$v];
                unset($vars[$v]);
            }
        }

        // replace variables to uri
        foreach ($matches[0] as $i => $inUriVar) {
            $uri = preg_replace('/\\'.$inUriVar.'/', $vars[$i], $uri, 1);
        }

        return $uri;
    }

    /**
     * Set allow override
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function setAllowOverride($bool = true)
    {
        $this->allowOverride = (boolean) $bool;

        return $this;
    }

    /**
     * Is allowing to override a pathName ?
     *
     * @return bool
     */
    public function isAllowOverride()
    {
        return $this->allowOverride;
    }

    /**
     * Set keyValue pair of pathName and Uri
     *
     * @param array $pathNames
     *
     * @throws \Exception
     * @return $this
     */
    public function setPaths(array $pathNames)
    {
        if (!empty($pathNames) && array_values($pathNames) == $pathNames)
            throw new \Exception('Variable Arrays Must Be Associated.');


        foreach ($pathNames as $name => $uri) {
            $this->setPath($name, $uri);
        }

        return $this;
    }

    /**
     * Set pathName and uri
     *
     * @param string $name
     * @param string $uri
     *
     * @return $this
     * @throws \Exception
     */
    public function setPath($name, $uri)
    {
        if ($this->hasPath($name) && !$this->isAllowOverride()) {
            throw new \Exception(
                sprintf('Path with name "%s" already exists and class not allow override it.', $name)
            );
        }

        $n = $this->normalizePathName($name);

        $this->pathNames[$n] = (string) $uri;

        return $this;
    }

    /**
     * Get pathName uri
     *
     * @param string $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function getPath($name)
    {
        $n = $this->normalizePathName($name);

        if (! $this->hasPath($n)) {
            throw new \Exception(sprintf('Path with name "%s" not found.', $name));
        }

        return $this->pathNames[$n];
    }

    /**
     * Return all registered pathnames with uri
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->pathNames;
    }

    /**
     * Determine that pathname is exists ?
     *
     * @param $name
     *
     * @return bool
     */
    public function hasPath($name)
    {
        $n = $this->normalizePathName($name);

        return isset($this->pathNames[$n]);
    }

    /**
     * Set Variable that replaced in uri's
     *
     * @param $var
     *
     * @param $value
     * @return $this
     */
    public function setVariable($var, $value)
    {
        $var = ltrim($var, '$');

        $this->variables[$var] = $value;

        return $this;
    }

    /**
     * Set Variables
     *
     * @param array $variables Variables
     *
     * @return $this
     * @throws \Exception
     */
    public function setVariables(array $variables)
    {
        if (!empty($variables) && array_values($variables) == $variables)
            throw new \Exception('Variable Arrays Must Be Associated.');

        foreach($variables as $var => $val)
            $this->setVariable($var, $val);

        return $this;
    }

    /**
     * Get variable
     *
     * @param string $var Name of variable
     *
     * @return null
     */
    public function getVariable($var)
    {
        return (isset($this->variables[$var])) ? $this->variables[$var] : null;
    }

    /**
     * Get registered variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Normalize path names for storing
     *
     * @param $name
     *
     * @return string
     */
    protected function normalizePathName($name)
    {
        return strtolower((string) $name);
    }

    // ------

    /**
     * Get basePath
     *
     * @throws \Exception
     * @return string
     */
    public function getBasePath()
    {
        $basepath =  $this->getVariable(self::PATH_BASE_PATH);
        if ($basepath === null)
            throw new \Exception('Basepath Not Set Yet!');

        return $basepath;
    }

    /**
     * Get serverUrl from view helper
     *
     * must same as view serverUrl
     *
     * @throws \Exception
     * @return string
     */
    public function getServerUrl()
    {
        $serverUrl = $this->getVariable(self::PATH_SERVER_URL);
        if (empty($serverUrl))
            throw new \Exception('ServerUrl Returned Empty Value.');

        return $serverUrl;
    }
}
