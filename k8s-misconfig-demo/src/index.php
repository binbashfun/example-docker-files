<?php
header('Content-Type: application/json');

function generateRandomString($length = 8) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

$podData = [
    "kind" => "Pod",
    "apiVersion" => "v1",
    "metadata" => [
        "name" => "worker-pod-" . generateRandomString(5),
        "namespace" => "default",
        "selfLink" => "/api/v1/namespaces/default/pods/" . generateRandomString(5),
        "uid" => generateRandomString(36),
        "resourceVersion" => (string)rand(100000, 999999),
        "creationTimestamp" => date("Y-m-d\TH:i:s\Z", strtotime("-" . rand(1, 30) . " days")),
        "labels" => [
            "app" => "worker-app",
            "environment" => "production"
        ],
        "annotations" => [
            "kubectl.kubernetes.io/last-applied-configuration" => "true"
        ]
    ],
    "spec" => [
        "volumes" => [
            [
                "name" => "default-token-" . generateRandomString(5),
                "secret" => [
                    "secretName" => "default-token-" . generateRandomString(5)
                ]
            ]
        ],
        "containers" => [
            [
                "name" => "web-server",
                "image" => "nginx:1.19",
                "ports" => [
                    ["containerPort" => 80, "protocol" => "TCP"]
                ],
                "env" => [
                    ["name" => "ENV", "value" => "production"],
                    ["name" => "SECRET_KEY", "value" => generateRandomString(32)]
                ],
                "resources" => [
                    "limits" => [
                        "cpu" => "500m",
                        "memory" => "128Mi"
                    ],
                    "requests" => [
                        "cpu" => "250m",
                        "memory" => "64Mi"
                    ]
                ]
            ],
            [
                "name" => "sidecar-logger",
                "image" => "busybox:1.32",
                "args" => ["sleep", "3600"],
                "resources" => [
                    "limits" => [
                        "cpu" => "100m",
                        "memory" => "32Mi"
                    ]
                ]
            ]
        ],
        "restartPolicy" => "Always",
        "nodeSelector" => [
            "kubernetes.io/hostname" => "node-" . rand(1, 5)
        ]
    ],
    "status" => [
        "phase" => "Running",
        "conditions" => [
            [
                "type" => "Initialized",
                "status" => "True",
                "lastProbeTime" => null,
                "lastTransitionTime" => date("Y-m-d\TH:i:s\Z", strtotime("-" . rand(1, 30) . " days"))
            ],
            [
                "type" => "Ready",
                "status" => "True",
                "lastProbeTime" => null,
                "lastTransitionTime" => date("Y-m-d\TH:i:s\Z", strtotime("-" . rand(1, 30) . " days"))
            ],
            [
                "type" => "ContainersReady",
                "status" => "True",
                "lastProbeTime" => null,
                "lastTransitionTime" => date("Y-m-d\TH:i:s\Z", strtotime("-" . rand(1, 30) . " days"))
            ],
            [
                "type" => "PodScheduled",
                "status" => "True",
                "lastProbeTime" => null,
                "lastTransitionTime" => date("Y-m-d\TH:i:s\Z", strtotime("-" . rand(1, 30) . " days"))
            ]
        ],
        "hostIP" => "192.168." . rand(1, 255) . "." . rand(1, 255),
        "podIP" => "10.0." . rand(1, 255) . "." . rand(1, 255),
        "startTime" => date("Y-m-d\TH:i:s\Z", strtotime("-" . rand(1, 30) . " days"))
    ]
];

header('Content-Type: application/json');
echo json_encode($podData, JSON_PRETTY_PRINT);
?>

