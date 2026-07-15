import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/impression',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
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
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:32
 * @route '/impression/membres'
 */
export const members = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: members.url(options),
    method: 'post',
})

members.definition = {
    methods: ["post"],
    url: '/impression/membres',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:32
 * @route '/impression/membres'
 */
members.url = (options?: RouteQueryOptions) => {
    return members.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:32
 * @route '/impression/membres'
 */
members.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: members.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:32
 * @route '/impression/membres'
 */
    const membersForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: members.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:32
 * @route '/impression/membres'
 */
        membersForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: members.url(options),
            method: 'post',
        })
    
    members.form = membersForm
/**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:41
 * @route '/impression/visiteurs'
 */
export const visitors = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: visitors.url(options),
    method: 'post',
})

visitors.definition = {
    methods: ["post"],
    url: '/impression/visiteurs',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:41
 * @route '/impression/visiteurs'
 */
visitors.url = (options?: RouteQueryOptions) => {
    return visitors.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:41
 * @route '/impression/visiteurs'
 */
visitors.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: visitors.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:41
 * @route '/impression/visiteurs'
 */
    const visitorsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: visitors.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:41
 * @route '/impression/visiteurs'
 */
        visitorsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: visitors.url(options),
            method: 'post',
        })
    
    visitors.form = visitorsForm
/**
* @see \App\Http\Controllers\PrintController::printMembersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
export const printMembersList = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printMembersList.url(options),
    method: 'get',
})

printMembersList.definition = {
    methods: ["get","head"],
    url: '/impression/liste-membres',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::printMembersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
printMembersList.url = (options?: RouteQueryOptions) => {
    return printMembersList.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::printMembersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
printMembersList.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printMembersList.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::printMembersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
printMembersList.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: printMembersList.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::printMembersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
    const printMembersListForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: printMembersList.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::printMembersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
        printMembersListForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printMembersList.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::printMembersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
        printMembersListForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printMembersList.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    printMembersList.form = printMembersListForm
/**
* @see \App\Http\Controllers\PrintController::printMonthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
export const printMonthlyAttendances = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printMonthlyAttendances.url(options),
    method: 'get',
})

printMonthlyAttendances.definition = {
    methods: ["get","head"],
    url: '/impression/presences-mois',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::printMonthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
printMonthlyAttendances.url = (options?: RouteQueryOptions) => {
    return printMonthlyAttendances.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::printMonthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
printMonthlyAttendances.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printMonthlyAttendances.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::printMonthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
printMonthlyAttendances.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: printMonthlyAttendances.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::printMonthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
    const printMonthlyAttendancesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: printMonthlyAttendances.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::printMonthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
        printMonthlyAttendancesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printMonthlyAttendances.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::printMonthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
        printMonthlyAttendancesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printMonthlyAttendances.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    printMonthlyAttendances.form = printMonthlyAttendancesForm
/**
* @see \App\Http\Controllers\PrintController::printCommunionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
export const printCommunionPrepared = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printCommunionPrepared.url(options),
    method: 'get',
})

printCommunionPrepared.definition = {
    methods: ["get","head"],
    url: '/impression/communion-prepares',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::printCommunionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
printCommunionPrepared.url = (options?: RouteQueryOptions) => {
    return printCommunionPrepared.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::printCommunionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
printCommunionPrepared.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printCommunionPrepared.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::printCommunionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
printCommunionPrepared.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: printCommunionPrepared.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::printCommunionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
    const printCommunionPreparedForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: printCommunionPrepared.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::printCommunionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
        printCommunionPreparedForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printCommunionPrepared.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::printCommunionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
        printCommunionPreparedForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printCommunionPrepared.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    printCommunionPrepared.form = printCommunionPreparedForm
/**
* @see \App\Http\Controllers\PrintController::printAbsentsCulte
 * @see app/Http/Controllers/PrintController.php:122
 * @route '/impression/absents-culte'
 */
export const printAbsentsCulte = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printAbsentsCulte.url(options),
    method: 'get',
})

printAbsentsCulte.definition = {
    methods: ["get","head"],
    url: '/impression/absents-culte',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::printAbsentsCulte
 * @see app/Http/Controllers/PrintController.php:122
 * @route '/impression/absents-culte'
 */
printAbsentsCulte.url = (options?: RouteQueryOptions) => {
    return printAbsentsCulte.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::printAbsentsCulte
 * @see app/Http/Controllers/PrintController.php:122
 * @route '/impression/absents-culte'
 */
printAbsentsCulte.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printAbsentsCulte.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::printAbsentsCulte
 * @see app/Http/Controllers/PrintController.php:122
 * @route '/impression/absents-culte'
 */
printAbsentsCulte.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: printAbsentsCulte.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::printAbsentsCulte
 * @see app/Http/Controllers/PrintController.php:122
 * @route '/impression/absents-culte'
 */
    const printAbsentsCulteForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: printAbsentsCulte.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::printAbsentsCulte
 * @see app/Http/Controllers/PrintController.php:122
 * @route '/impression/absents-culte'
 */
        printAbsentsCulteForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printAbsentsCulte.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::printAbsentsCulte
 * @see app/Http/Controllers/PrintController.php:122
 * @route '/impression/absents-culte'
 */
        printAbsentsCulteForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printAbsentsCulte.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    printAbsentsCulte.form = printAbsentsCulteForm
/**
* @see \App\Http\Controllers\PrintController::printNotPreparedCommunion
 * @see app/Http/Controllers/PrintController.php:164
 * @route '/impression/communion-non-prepares'
 */
export const printNotPreparedCommunion = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printNotPreparedCommunion.url(options),
    method: 'get',
})

printNotPreparedCommunion.definition = {
    methods: ["get","head"],
    url: '/impression/communion-non-prepares',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::printNotPreparedCommunion
 * @see app/Http/Controllers/PrintController.php:164
 * @route '/impression/communion-non-prepares'
 */
printNotPreparedCommunion.url = (options?: RouteQueryOptions) => {
    return printNotPreparedCommunion.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::printNotPreparedCommunion
 * @see app/Http/Controllers/PrintController.php:164
 * @route '/impression/communion-non-prepares'
 */
printNotPreparedCommunion.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: printNotPreparedCommunion.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::printNotPreparedCommunion
 * @see app/Http/Controllers/PrintController.php:164
 * @route '/impression/communion-non-prepares'
 */
printNotPreparedCommunion.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: printNotPreparedCommunion.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::printNotPreparedCommunion
 * @see app/Http/Controllers/PrintController.php:164
 * @route '/impression/communion-non-prepares'
 */
    const printNotPreparedCommunionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: printNotPreparedCommunion.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::printNotPreparedCommunion
 * @see app/Http/Controllers/PrintController.php:164
 * @route '/impression/communion-non-prepares'
 */
        printNotPreparedCommunionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printNotPreparedCommunion.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::printNotPreparedCommunion
 * @see app/Http/Controllers/PrintController.php:164
 * @route '/impression/communion-non-prepares'
 */
        printNotPreparedCommunionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: printNotPreparedCommunion.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    printNotPreparedCommunion.form = printNotPreparedCommunionForm
const PrintController = { index, members, visitors, printMembersList, printMonthlyAttendances, printCommunionPrepared, printAbsentsCulte, printNotPreparedCommunion }

export default PrintController