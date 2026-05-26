import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\VisitorController::index
 * @see app/Http/Controllers/VisitorController.php:21
 * @route '/visitors'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/visitors',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VisitorController::index
 * @see app/Http/Controllers/VisitorController.php:21
 * @route '/visitors'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VisitorController::index
 * @see app/Http/Controllers/VisitorController.php:21
 * @route '/visitors'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\VisitorController::index
 * @see app/Http/Controllers/VisitorController.php:21
 * @route '/visitors'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\VisitorController::index
 * @see app/Http/Controllers/VisitorController.php:21
 * @route '/visitors'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\VisitorController::index
 * @see app/Http/Controllers/VisitorController.php:21
 * @route '/visitors'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\VisitorController::index
 * @see app/Http/Controllers/VisitorController.php:21
 * @route '/visitors'
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
* @see \App\Http\Controllers\VisitorController::create
 * @see app/Http/Controllers/VisitorController.php:28
 * @route '/visitors/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/visitors/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VisitorController::create
 * @see app/Http/Controllers/VisitorController.php:28
 * @route '/visitors/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VisitorController::create
 * @see app/Http/Controllers/VisitorController.php:28
 * @route '/visitors/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\VisitorController::create
 * @see app/Http/Controllers/VisitorController.php:28
 * @route '/visitors/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\VisitorController::create
 * @see app/Http/Controllers/VisitorController.php:28
 * @route '/visitors/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\VisitorController::create
 * @see app/Http/Controllers/VisitorController.php:28
 * @route '/visitors/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\VisitorController::create
 * @see app/Http/Controllers/VisitorController.php:28
 * @route '/visitors/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\VisitorController::store
 * @see app/Http/Controllers/VisitorController.php:33
 * @route '/visitors'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/visitors',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VisitorController::store
 * @see app/Http/Controllers/VisitorController.php:33
 * @route '/visitors'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VisitorController::store
 * @see app/Http/Controllers/VisitorController.php:33
 * @route '/visitors'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\VisitorController::store
 * @see app/Http/Controllers/VisitorController.php:33
 * @route '/visitors'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\VisitorController::store
 * @see app/Http/Controllers/VisitorController.php:33
 * @route '/visitors'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\VisitorController::show
 * @see app/Http/Controllers/VisitorController.php:41
 * @route '/visitors/{visitor}'
 */
export const show = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/visitors/{visitor}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VisitorController::show
 * @see app/Http/Controllers/VisitorController.php:41
 * @route '/visitors/{visitor}'
 */
show.url = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { visitor: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { visitor: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    visitor: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        visitor: typeof args.visitor === 'object'
                ? args.visitor.id
                : args.visitor,
                }

    return show.definition.url
            .replace('{visitor}', parsedArgs.visitor.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VisitorController::show
 * @see app/Http/Controllers/VisitorController.php:41
 * @route '/visitors/{visitor}'
 */
show.get = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\VisitorController::show
 * @see app/Http/Controllers/VisitorController.php:41
 * @route '/visitors/{visitor}'
 */
show.head = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\VisitorController::show
 * @see app/Http/Controllers/VisitorController.php:41
 * @route '/visitors/{visitor}'
 */
    const showForm = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\VisitorController::show
 * @see app/Http/Controllers/VisitorController.php:41
 * @route '/visitors/{visitor}'
 */
        showForm.get = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\VisitorController::show
 * @see app/Http/Controllers/VisitorController.php:41
 * @route '/visitors/{visitor}'
 */
        showForm.head = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\VisitorController::convert
 * @see app/Http/Controllers/VisitorController.php:46
 * @route '/visitors/{visitor}/convert'
 */
export const convert = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: convert.url(args, options),
    method: 'post',
})

convert.definition = {
    methods: ["post"],
    url: '/visitors/{visitor}/convert',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VisitorController::convert
 * @see app/Http/Controllers/VisitorController.php:46
 * @route '/visitors/{visitor}/convert'
 */
convert.url = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { visitor: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { visitor: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    visitor: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        visitor: typeof args.visitor === 'object'
                ? args.visitor.id
                : args.visitor,
                }

    return convert.definition.url
            .replace('{visitor}', parsedArgs.visitor.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VisitorController::convert
 * @see app/Http/Controllers/VisitorController.php:46
 * @route '/visitors/{visitor}/convert'
 */
convert.post = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: convert.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\VisitorController::convert
 * @see app/Http/Controllers/VisitorController.php:46
 * @route '/visitors/{visitor}/convert'
 */
    const convertForm = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: convert.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\VisitorController::convert
 * @see app/Http/Controllers/VisitorController.php:46
 * @route '/visitors/{visitor}/convert'
 */
        convertForm.post = (args: { visitor: number | { id: number } } | [visitor: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: convert.url(args, options),
            method: 'post',
        })
    
    convert.form = convertForm
const VisitorController = { index, create, store, show, convert }

export default VisitorController