import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ScannerController::index
 * @see app/Http/Controllers/ScannerController.php:20
 * @route '/scanner'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/scanner',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ScannerController::index
 * @see app/Http/Controllers/ScannerController.php:20
 * @route '/scanner'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ScannerController::index
 * @see app/Http/Controllers/ScannerController.php:20
 * @route '/scanner'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ScannerController::index
 * @see app/Http/Controllers/ScannerController.php:20
 * @route '/scanner'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ScannerController::index
 * @see app/Http/Controllers/ScannerController.php:20
 * @route '/scanner'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ScannerController::index
 * @see app/Http/Controllers/ScannerController.php:20
 * @route '/scanner'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ScannerController::index
 * @see app/Http/Controllers/ScannerController.php:20
 * @route '/scanner'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\ScannerController::scan
 * @see app/Http/Controllers/ScannerController.php:39
 * @route '/scanner/scan'
 */
export const scan = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: scan.url(options),
    method: 'post',
})

scan.definition = {
    methods: ["post"],
    url: '/scanner/scan',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ScannerController::scan
 * @see app/Http/Controllers/ScannerController.php:39
 * @route '/scanner/scan'
 */
scan.url = (options?: RouteQueryOptions) => {
    return scan.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ScannerController::scan
 * @see app/Http/Controllers/ScannerController.php:39
 * @route '/scanner/scan'
 */
scan.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: scan.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ScannerController::scan
 * @see app/Http/Controllers/ScannerController.php:39
 * @route '/scanner/scan'
 */
    const scanForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: scan.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ScannerController::scan
 * @see app/Http/Controllers/ScannerController.php:39
 * @route '/scanner/scan'
 */
        scanForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: scan.url(options),
            method: 'post',
        })
    
    scan.form = scanForm
const ScannerController = { index, scan }

export default ScannerController