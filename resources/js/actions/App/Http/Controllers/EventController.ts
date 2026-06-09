import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\EventController::index
 * @see app/Http/Controllers/EventController.php:18
 * @route '/admin-events'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin-events',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EventController::index
 * @see app/Http/Controllers/EventController.php:18
 * @route '/admin-events'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EventController::index
 * @see app/Http/Controllers/EventController.php:18
 * @route '/admin-events'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\EventController::index
 * @see app/Http/Controllers/EventController.php:18
 * @route '/admin-events'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\EventController::index
 * @see app/Http/Controllers/EventController.php:18
 * @route '/admin-events'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\EventController::index
 * @see app/Http/Controllers/EventController.php:18
 * @route '/admin-events'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\EventController::index
 * @see app/Http/Controllers/EventController.php:18
 * @route '/admin-events'
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
* @see \App\Http\Controllers\EventController::create
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin-events/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EventController::create
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EventController::create
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\EventController::create
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\EventController::create
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\EventController::create
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\EventController::create
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/create'
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
* @see \App\Http\Controllers\EventController::store
 * @see app/Http/Controllers/EventController.php:41
 * @route '/admin-events'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin-events',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\EventController::store
 * @see app/Http/Controllers/EventController.php:41
 * @route '/admin-events'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EventController::store
 * @see app/Http/Controllers/EventController.php:41
 * @route '/admin-events'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\EventController::store
 * @see app/Http/Controllers/EventController.php:41
 * @route '/admin-events'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\EventController::store
 * @see app/Http/Controllers/EventController.php:41
 * @route '/admin-events'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\EventController::show
 * @see app/Http/Controllers/EventController.php:72
 * @route '/admin-events/{admin_event}'
 */
export const show = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin-events/{admin_event}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EventController::show
 * @see app/Http/Controllers/EventController.php:72
 * @route '/admin-events/{admin_event}'
 */
show.url = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { admin_event: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    admin_event: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        admin_event: args.admin_event,
                }

    return show.definition.url
            .replace('{admin_event}', parsedArgs.admin_event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EventController::show
 * @see app/Http/Controllers/EventController.php:72
 * @route '/admin-events/{admin_event}'
 */
show.get = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\EventController::show
 * @see app/Http/Controllers/EventController.php:72
 * @route '/admin-events/{admin_event}'
 */
show.head = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\EventController::show
 * @see app/Http/Controllers/EventController.php:72
 * @route '/admin-events/{admin_event}'
 */
    const showForm = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\EventController::show
 * @see app/Http/Controllers/EventController.php:72
 * @route '/admin-events/{admin_event}'
 */
        showForm.get = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\EventController::show
 * @see app/Http/Controllers/EventController.php:72
 * @route '/admin-events/{admin_event}'
 */
        showForm.head = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\EventController::edit
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/{admin_event}/edit'
 */
export const edit = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin-events/{admin_event}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EventController::edit
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/{admin_event}/edit'
 */
edit.url = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { admin_event: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    admin_event: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        admin_event: args.admin_event,
                }

    return edit.definition.url
            .replace('{admin_event}', parsedArgs.admin_event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EventController::edit
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/{admin_event}/edit'
 */
edit.get = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\EventController::edit
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/{admin_event}/edit'
 */
edit.head = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\EventController::edit
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/{admin_event}/edit'
 */
    const editForm = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\EventController::edit
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/{admin_event}/edit'
 */
        editForm.get = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\EventController::edit
 * @see app/Http/Controllers/EventController.php:0
 * @route '/admin-events/{admin_event}/edit'
 */
        editForm.head = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\EventController::update
 * @see app/Http/Controllers/EventController.php:119
 * @route '/admin-events/{admin_event}'
 */
export const update = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin-events/{admin_event}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\EventController::update
 * @see app/Http/Controllers/EventController.php:119
 * @route '/admin-events/{admin_event}'
 */
update.url = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { admin_event: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    admin_event: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        admin_event: args.admin_event,
                }

    return update.definition.url
            .replace('{admin_event}', parsedArgs.admin_event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EventController::update
 * @see app/Http/Controllers/EventController.php:119
 * @route '/admin-events/{admin_event}'
 */
update.put = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\EventController::update
 * @see app/Http/Controllers/EventController.php:119
 * @route '/admin-events/{admin_event}'
 */
update.patch = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\EventController::update
 * @see app/Http/Controllers/EventController.php:119
 * @route '/admin-events/{admin_event}'
 */
    const updateForm = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\EventController::update
 * @see app/Http/Controllers/EventController.php:119
 * @route '/admin-events/{admin_event}'
 */
        updateForm.put = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\EventController::update
 * @see app/Http/Controllers/EventController.php:119
 * @route '/admin-events/{admin_event}'
 */
        updateForm.patch = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\EventController::destroy
 * @see app/Http/Controllers/EventController.php:154
 * @route '/admin-events/{admin_event}'
 */
export const destroy = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin-events/{admin_event}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\EventController::destroy
 * @see app/Http/Controllers/EventController.php:154
 * @route '/admin-events/{admin_event}'
 */
destroy.url = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { admin_event: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    admin_event: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        admin_event: args.admin_event,
                }

    return destroy.definition.url
            .replace('{admin_event}', parsedArgs.admin_event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EventController::destroy
 * @see app/Http/Controllers/EventController.php:154
 * @route '/admin-events/{admin_event}'
 */
destroy.delete = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\EventController::destroy
 * @see app/Http/Controllers/EventController.php:154
 * @route '/admin-events/{admin_event}'
 */
    const destroyForm = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\EventController::destroy
 * @see app/Http/Controllers/EventController.php:154
 * @route '/admin-events/{admin_event}'
 */
        destroyForm.delete = (args: { admin_event: string | number } | [admin_event: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Http\Controllers\EventController::storeContribution
 * @see app/Http/Controllers/EventController.php:168
 * @route '/admin-events/{event}/contributions'
 */
export const storeContribution = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeContribution.url(args, options),
    method: 'post',
})

storeContribution.definition = {
    methods: ["post"],
    url: '/admin-events/{event}/contributions',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\EventController::storeContribution
 * @see app/Http/Controllers/EventController.php:168
 * @route '/admin-events/{event}/contributions'
 */
storeContribution.url = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { event: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { event: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    event: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        event: typeof args.event === 'object'
                ? args.event.id
                : args.event,
                }

    return storeContribution.definition.url
            .replace('{event}', parsedArgs.event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EventController::storeContribution
 * @see app/Http/Controllers/EventController.php:168
 * @route '/admin-events/{event}/contributions'
 */
storeContribution.post = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeContribution.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\EventController::storeContribution
 * @see app/Http/Controllers/EventController.php:168
 * @route '/admin-events/{event}/contributions'
 */
    const storeContributionForm = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: storeContribution.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\EventController::storeContribution
 * @see app/Http/Controllers/EventController.php:168
 * @route '/admin-events/{event}/contributions'
 */
        storeContributionForm.post = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: storeContribution.url(args, options),
            method: 'post',
        })
    
    storeContribution.form = storeContributionForm
const EventController = { index, create, store, show, edit, update, destroy, storeContribution }

export default EventController